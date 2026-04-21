<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requirement;  
use App\Models\DocumentUpload;  
use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
{
    $query = User::whereIn('role', ['applicant', 'student'])
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
    $requiredCount = Requirement::count();

    foreach ($applicants as $applicant) {
        $latestUpload = DocumentUpload::where('user_id', $applicant->id)->latest()->first();
        $applicant->last_update = $latestUpload ? $latestUpload->created_at : $applicant->updated_at;
        
        $applicant->upload_count = DocumentUpload::where('user_id', $applicant->id)->count();
        
        // FIX: Kung wala pang manual status na naka-set, doon lang tayo mag-aassign ng system status
        if (empty($applicant->application_status)) {
            if ($applicant->upload_count == 0) {
                $applicant->application_status = 'Not Started';
            } elseif ($applicant->upload_count < $requiredCount) {
                $applicant->application_status = 'On Going';
            } else {
                $applicant->application_status = 'Completed';
            }
        }
    }
    
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
    try {
        $request->validate([
            'status' => 'required|string',
            'decision' => 'nullable|string'
        ]);
        
        $applicant = User::findOrFail($id);
        
        // Update the application_status field (this is what displays in the table)
        $applicant->application_status = $request->status;
        $applicant->decision_notes = $request->decision;
        $applicant->save();
        
        \Log::info('Status updated for user ' . $id . ' to ' . $request->status);
        \Log::info('Remarks: ' . $request->decision);
        
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully to ' . $request->status
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error updating status: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to update status: ' . $e->getMessage()
        ], 500);
    }
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
    $applicant->interview_status = 'scheduled';
    $applicant->interview_result = 'Pending'; // ADD THIS LINE
    
    // Save meeting link if provided
    if ($request->has('meeting_link')) {
        $applicant->interview_meeting_link = $request->meeting_link;
    }
    
    $applicant->save();
    
    return response()->json([
        'success' => true, 
        'message' => 'Interview scheduled successfully'
    ]);
}

public function cancelInterview(Request $request, $id)
{
    $applicant = User::findOrFail($id);
    $applicant->interview_setup = null;
    $applicant->interview_location = null;
    $applicant->interview_date = null;
    $applicant->interview_time = null;
    $applicant->interview_status = 'cancelled';  // ADD THIS LINE (or 'cancelled')
    $applicant->save();
    
    return response()->json([
        'success' => true, 
        'message' => 'Interview cancelled successfully'
    ]);
}

        public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string'
        ]);
        
        // Create message from staff to applicant
        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $id,
            'message' => $request->message,
            'is_read' => false
        ]);
        
        // If this is a document verification update, also store in document_uploads table
        if ($request->has('document_name') && $request->has('status')) {
            // You can also update the document_uploads table with the verification comment
            // This is already handled by updateDocumentVerification method
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully to applicant'
        ]);
    }

    public function updateInterviewResult(Request $request, $id)
{
    try {
        $applicant = User::findOrFail($id);
        
        $request->validate([
            'interview_result' => 'required|in:Pending,Passed,Failed'
        ]);
        
        $applicant->interview_result = $request->interview_result;
        
        // If Passed or Failed, update interview_status to Completed
        if (in_array($request->interview_result, ['Passed', 'Failed'])) {
            $applicant->interview_status = 'Completed';
        } else {
            $applicant->interview_status = 'Scheduled';
        }
        
        $applicant->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Interview result updated successfully to ' . $request->interview_result
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update interview result: ' . $e->getMessage()
        ], 500);
    }
}

// Add this method to your StaffDashboardController.php

public function sendPaymentStub(Request $request, $id)
{
    try {
        $applicant = User::findOrFail($id);
        
        // Generate reference number
        $reference = 'PAY-' . strtoupper(Str::random(8));
        
        // Update payment status to 'pending' (not paid yet)
        $applicant->update([
            'payment_status' => 'pending',
            'payment_reference' => $reference
        ]);
        
        // Insert payment stub task - payment_upload type (for uploading proof)
        DB::table('tasks')->insert([
            'user_id' => $id,
            'title' => 'Upload Payment Proof',
            'description' => "Payment Reference: {$reference}\n\nPlease upload your proof of payment for verification.",
            'action_url' => route('applicant.download-payment-stub', $id),
            'status' => 'pending',
            'type' => 'payment_upload', // Changed type
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // Create message
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $id,
            'message' => "Payment instruction has been added to your Todo list.\n\nReference Number: {$reference}\n\nPlease upload your proof of payment.",
            'is_read' => false
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Payment stub sent successfully!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send: ' . $e->getMessage()
        ], 500);
    }
}

public function verifyPayment(Request $request, $id)
{
    try {
        $applicant = User::findOrFail($id);
        
        $request->validate([
            'payment_status' => 'required|in:paid,rejected',
            'verification_note' => 'nullable|string'
        ]);
        
        // Update payment status (use lowercase)
        $applicant->payment_status = $request->payment_status; // 'paid' or 'rejected'
        $applicant->save();
        
        // If paid, complete the payment task
        if ($request->payment_status == 'paid') {
            DB::table('tasks')
                ->where('user_id', $id)
                ->where('type', 'payment_upload')
                ->where('status', 'pending')
                ->update(['status' => 'completed']);
        } else {
            // If rejected, clear the payment proof so applicant can re-upload
            $applicant->payment_proof = null;
            $applicant->payment_proof_uploaded_at = null;
            $applicant->payment_status = 'pending';
            $applicant->save();
        }
        
        // Notify applicant
        $statusMessage = $request->payment_status == 'paid' 
            ? "Your payment has been verified and approved!"
            : "Your payment proof was rejected. Reason: " . ($request->verification_note ?? 'Please re-upload valid proof.');
        
        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $id,
            'message' => $statusMessage,
            'is_read' => false
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Payment ' . ($request->payment_status == 'paid' ? 'verified' : 'rejected') . ' successfully!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to verify payment: ' . $e->getMessage()
        ], 500);
    }
}

// Add this method to handle payment proof upload from applicant
public function uploadPaymentProof(Request $request, $id)
{
    try {
        $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);
        
        $applicant = User::findOrFail($id);
        
        // Store the file
        $file = $request->file('payment_proof');
        $filename = 'payment_proof_' . $applicant->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment_proofs', $filename, 'public');
        
        // Save to database (you may need to create a PaymentProof model or add column to users table)
        // For now, let's add a column to users table or create a new table
        $applicant->payment_proof = $path;
        $applicant->payment_proof_uploaded_at = now();
        $applicant->payment_status = 'paid'; // Auto update to paid
        $applicant->save();
        
        // Complete the payment task
        DB::table('tasks')
            ->where('user_id', $id)
            ->where('type', 'payment')
            ->where('status', 'pending')
            ->update(['status' => 'completed']);
        
        // Notify staff
        Message::create([
            'sender_id' => $id,
            'receiver_id' => 1, // Assuming staff user ID 1 is admin, or get first staff
            'message' => "Applicant {$applicant->first_name} {$applicant->last_name} has uploaded their payment proof.",
            'is_read' => false
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Payment proof uploaded successfully!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to upload: ' . $e->getMessage()
        ], 500);
    }
}

}
