<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requirement;  
use App\Models\DocumentUpload;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'applicant')
            ->orWhere('role', 'student')
            ->orderBy('created_at', 'desc');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
            });
        }
        
        $applicants = $query->paginate(15);
        
        foreach ($applicants as $applicant) {

            $latestUpload = DocumentUpload::where('user_id', $applicant->id)
                ->latest()
                ->first();
            $applicant->last_update = $latestUpload ? $latestUpload->created_at : $applicant->updated_at;
            
            $applicant->upload_count = DocumentUpload::where('user_id', $applicant->id)->count();
            
            $requiredCount = Requirement::count();
            if ($applicant->upload_count == 0) {
                $applicant->status = 'Not Started';
                $applicant->status_color = 'gray';
            } elseif ($applicant->upload_count < $requiredCount) {
                $applicant->status = 'On Going';
                $applicant->status_color = 'orange';
            } else {
                $applicant->status = 'Completed';
                $applicant->status_color = 'green';
            }
        }
        
        // Get statistics
        $stats = [
            'pending_reviews' => DocumentUpload::where('status', 'Pending')->count(),
            'new_applications' => User::where('role', 'applicant')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'document_issues' => DocumentUpload::where('status', 'Rejected')->count(),
        ];
        
        return view('staff_dash', compact('applicants', 'stats'));
    }
    
    public function getApplicantDetails($id)
    {
        $applicant = User::findOrFail($id);
        
        $birthdate = 'Not specified';
        if ($applicant->birthdate && $applicant->birthdate != '0000-00-00') {
            try {
                $birthdate = date('F d, Y', strtotime($applicant->birthdate));
            } catch (\Exception $e) {
                $birthdate = $applicant->birthdate;
            }
        }
        
        $documents = DocumentUpload::where('user_id', $id)
            ->with('requirement')
            ->get()
            ->map(function($doc) {
                return [
                    'name' => $doc->requirement->name,
                    'file_path' => asset('storage/' . $doc->file_path),
                    'upload_date' => $doc->created_at->format('Y-m-d H:i:s'),
                    'status' => $doc->status
                ];
            });
        
        return response()->json([
            'success' => true,
            'applicant' => [
                'id' => $applicant->id,
                'first_name' => $applicant->first_name,
                'last_name' => $applicant->last_name,
                'middle_name' => $applicant->middle_name,
                'extension_name' => $applicant->extension_name,
                'email' => $applicant->email,
                'sex' => ucfirst(strtolower($applicant->sex ?? 'Not specified')),
                'birthdate' => $birthdate,
                'permanent_address' => $applicant->permanent_address ?? 'Not specified',
                'current_address' => $applicant->current_address ?? 'Not specified',
                'degree_program' => $applicant->degree_program ?? 'Not specified',
                'status' => $applicant->application_status ?? 'Pending',
                'profile_image' => $applicant->profile_image ? asset('storage/' . $applicant->profile_image) : null,
                'created_at' => $applicant->created_at->format('F d, Y'),
            ],
            'documents' => $documents,
            'upload_count' => $documents->count()
        ]);
    }
    
    public function updateApplicantStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'decision' => 'nullable|string'
        ]);
        
        $applicant = User::findOrFail($id);
        $applicant->application_status = $request->status;
        $applicant->decision_notes = $request->decision;
        $applicant->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        $applicant = User::findOrFail($id);
        $applicant->application_status = $request->status;
        $applicant->save();
        
        return response()->json(['success' => true, 'message' => 'Application status updated']);
    }

    public function updateDocumentStatus(Request $request, $id)
    {
        $applicant = User::findOrFail($id);
        $applicant->document_status = $request->document_status;
        $applicant->save();
        
        return response()->json(['success' => true, 'message' => 'Document status updated']);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $applicant = User::findOrFail($id);
        $applicant->payment_status = $request->payment_status;
        $applicant->save();
        
        return response()->json(['success' => true, 'message' => 'Payment status updated']);
    }

    public function updateFinalStatus(Request $request, $id)
    {
        $applicant = User::findOrFail($id);
        $applicant->final_status = $request->final_status;
        $applicant->save();
        
        return response()->json(['success' => true, 'message' => 'Final review status updated']);
    }

    public function viewApplicantInfo($id)
    {
        $applicant = User::findOrFail($id);
        $documentCount = DocumentUpload::where('user_id', $id)->count();
        $requiredCount = Requirement::count();
        
        return view('applicant_info', compact('applicant', 'documentCount', 'requiredCount'));
    }

    public function viewApplicantDocuments($id)
    {
        $applicant = User::findOrFail($id);
        $requirements = Requirement::all();
        
        // Get document uploads for this specific applicant
        $documents = DocumentUpload::where('user_id', $id)
            ->with('requirement')
            ->get()
            ->keyBy('requirement_id');
        
        // Get recent uploads for the recent table
        $recentUploads = DocumentUpload::where('user_id', $id)
            ->with('requirement')
            ->latest()
            ->take(5)
            ->get();
        
        return view('document_verification', compact('applicant', 'requirements', 'documents', 'recentUploads'));
    }

    public function getDocumentDetails($id, $requirementId)
{
    $document = DocumentUpload::where('user_id', $id)
        ->where('requirement_id', $requirementId)
        ->with('requirement')
        ->first();
    
    if ($document) {
        return response()->json([
            'success' => true,
            'document' => [
                'id' => $document->id,
                'file_path' => Storage::url($document->file_path),
                'file_name' => $document->file_name,
                'upload_date' => $document->created_at->toISOString(), // Use ISO string for consistent parsing
                'status' => $document->status,
                'verification_reason' => $document->verification_reason ?? null,
                'verification_comment' => $document->verification_comment ?? null,
                'is_reuploaded' => $document->is_reuploaded ?? false,
                'reuploaded_at' => $document->reuploaded_at ? $document->reuploaded_at->toISOString() : null
            ]
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'No document uploaded yet'
    ]);
}

    public function updateDocumentVerification(Request $request, $id)
    {
        $request->validate([
            'requirement_id' => 'required|exists:requirements,id',
            'status' => 'required|in:approved,incomplete,rejected',
            'reason' => 'nullable|string',
            'comment' => 'nullable|string'
        ]);
        
        $document = DocumentUpload::where('user_id', $id)
        ->where('requirement_id', $request->requirement_id)
        ->first();
    
    if (!$document) {
        return response()->json([
            'success' => false,
            'message' => 'Document not found'
        ]);
    }
    
    $document->status = $request->status;
    $document->verification_reason = $request->reason;
    $document->verification_comment = $request->comment;
    $document->is_reuploaded = false; 
    $document->save();
    
    return response()->json([
        'success' => true,
        'message' => 'Verification updated successfully'
    ]);
}

    public function setInterview(Request $request, $id)
    {
        $applicant = User::findOrFail($id);
        $applicant->interview_setup = $request->setup;
        $applicant->interview_location = $request->location;
        $applicant->interview_date = $request->date;
        $applicant->interview_time = $request->time;
        $applicant->save();
        
        return response()->json(['success' => true, 'message' => 'Interview scheduled successfully']);
    }

    public function sendMessage(Request $request, $id)
    {
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }
}