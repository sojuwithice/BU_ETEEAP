<?php

namespace App\Http\Controllers;

use App\Models\Requirement;
use App\Models\DocumentUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantDocumentController extends Controller
{
    public function index()
    {
        $requirements = Requirement::with(['userUpload' => function($query) {
            $query->where('user_id', auth()->id());
        }])->get();

        $recentUploads = DocumentUpload::where('user_id', auth()->id())
            ->with('requirement')
            ->latest()
            ->get();

        return view('applicant_documents', compact('requirements', 'recentUploads'));
    }

    public function storeUpload(Request $request)
    {
        $request->validate([
            'requirement_id' => 'required|exists:requirements,id',
            'submission_type' => 'required|in:file_upload,gdrive_link'
        ]);
        
        $requirementId = $request->requirement_id;
        $submissionType = $request->submission_type;
        
        $existingUpload = DocumentUpload::where('user_id', auth()->id())
            ->where('requirement_id', $requirementId)
            ->first();
        
        $isReupload = false;
        if ($existingUpload && in_array(strtolower($existingUpload->status ?? ''), ['rejected', 'incomplete'])) {
            $isReupload = true;
        }
        
        if ($submissionType === 'file_upload') {
            // File upload validation
            $request->validate([
                'file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:5120',
            ]);
            
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $path = $file->storeAs('documents', $fileName, 'public');
            
            $upload = DocumentUpload::updateOrCreate(
                ['user_id' => auth()->id(), 'requirement_id' => $requirementId],
                [
                    'submission_type' => 'file_upload',
                    'file_path' => $path,
                    'submission_value' => null,
                    'status' => 'pending',
                    'file_name' => $originalName,
                    'is_reuploaded' => $isReupload,
                    'reuploaded_at' => $isReupload ? now() : null,
                    'verification_reason' => null,
                    'verification_comment' => null
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => $isReupload ? 'File re-uploaded successfully! It will be reviewed again.' : 'File saved successfully!',
                'file_path' => Storage::url($path),
                'path' => $path,
                'file_name' => $originalName,
                'is_reuploaded' => $isReupload,
                'submission_type' => 'file_upload'
            ]);
            
        } else { // gdrive_link submission
            $request->validate([
                'submission_value' => 'required|url'
            ]);
            
            $gdriveLink = $request->submission_value;
            
            $upload = DocumentUpload::updateOrCreate(
                ['user_id' => auth()->id(), 'requirement_id' => $requirementId],
                [
                    'submission_type' => 'gdrive_link',
                    'file_path' => null,
                    'submission_value' => $gdriveLink,
                    'status' => 'pending',
                    'file_name' => null,
                    'is_reuploaded' => $isReupload,
                    'reuploaded_at' => $isReupload ? now() : null,
                    'verification_reason' => null,
                    'verification_comment' => null
                ]
            );
            
            return response()->json([
                'success' => true,
                'message' => $isReupload ? 'Google Drive link re-submitted successfully! It will be reviewed again.' : 'Google Drive link saved successfully!',
                'submission_value' => $gdriveLink,
                'submission_type' => 'gdrive_link',
                'is_reuploaded' => $isReupload
            ]);
        }
    }
    
    // ADD THIS METHOD FOR UPDATING GOOGLE DRIVE LINK
    public function updateUpload(Request $request)
    {
        $request->validate([
            'requirement_id' => 'required|exists:requirements,id',
            'submission_type' => 'required|in:gdrive_link',
            'submission_value' => 'required|url'
        ]);
        
        $requirementId = $request->requirement_id;
        $gdriveLink = $request->submission_value;
        
        $upload = DocumentUpload::where('user_id', auth()->id())
            ->where('requirement_id', $requirementId)
            ->first();
        
        if (!$upload) {
            return response()->json(['success' => false, 'message' => 'No existing upload found'], 404);
        }
        
        $upload->update([
            'submission_value' => $gdriveLink,
            'status' => 'pending',
            'verification_reason' => null,
            'verification_comment' => null,
            'is_reuploaded' => true,
            'reuploaded_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Google Drive link updated successfully! It will be reviewed again.',
            'submission_value' => $gdriveLink,
            'submission_type' => 'gdrive_link'
        ]);
    }

    public function destroyUpload(Request $request)
    {
        $upload = DocumentUpload::where('user_id', auth()->id())
            ->where('requirement_id', $request->requirement_id)
            ->first();

        if ($upload) {
            // Delete file only if it's a file upload
            if ($upload->submission_type === 'file_upload' && $upload->file_path) {
                Storage::disk('public')->delete($upload->file_path);
            }
            $upload->delete();

            return response()->json(['success' => true, 'message' => 'Document removed successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'Document not found.'], 404);
    }

    // ================= ONSITE VERIFICATION METHODS =================
    
    public function requestOnsiteVerification(Request $request)
    {
        $user = auth()->user();
        
        $user->onsite_verification_pending = true;
        $user->save();
        
        return response()->json(['success' => true, 'message' => 'Onsite verification requested']);
    }

    public function getOnsiteStatus()
    {
        $user = auth()->user();
        
        return response()->json([
            'pending_verification' => $user->onsite_verification_pending ?? false,
            'verified' => $user->onsite_verified ?? false
        ]);
    }
    
    public function confirmOnsiteSubmission(Request $request)
    {
        // For staff to confirm
        $user = \App\Models\User::find($request->user_id);
        
        if ($user) {
            $user->onsite_verification_pending = false;
            $user->onsite_verified = true;
            $user->save();
            
            return response()->json(['success' => true, 'message' => 'Onsite submission confirmed!']);
        }
        
        return response()->json(['success' => false, 'message' => 'User not found'], 404);
    }
}