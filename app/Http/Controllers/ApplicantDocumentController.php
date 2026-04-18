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
            'file' => 'required|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:5120', // 5MB limit
        ]);

        // I-save ang file sa storage/app/public/documents
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $path = $file->storeAs('documents', $fileName, 'public');

        // Kung may dati nang upload, i-update; kung wala, gawa ng bago
        $upload = DocumentUpload::updateOrCreate(
            ['user_id' => auth()->id(), 'requirement_id' => $request->requirement_id],
            ['file_path' => $path, 'status' => 'Pending', 'file_name' => $originalName]
        );

        return response()->json([
            'success' => true, 
            'message' => 'File saved successfully!',
            'file_path' => Storage::url($path),
            'path' => $path,
            'file_name' => $originalName
        ]);
    }

    public function destroyUpload(Request $request)
    {
        $upload = DocumentUpload::where('user_id', auth()->id())
            ->where('requirement_id', $request->requirement_id)
            ->first();

        if ($upload) {
            // Burahin ang file sa storage
            Storage::disk('public')->delete($upload->file_path);
            // Burahin ang record sa database
            $upload->delete();

            return response()->json(['success' => true, 'message' => 'File removed successfully!']);
        }

        return response()->json(['success' => false, 'message' => 'File not found.'], 404);
    }

     // ================= ONSITE VERIFICATION METHODS =================
    
    public function requestOnsiteVerification(Request $request)
    {
        $user = auth()->user();
        
        // Update user record to mark pending onsite verification
        // Need to add these columns to users table first
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