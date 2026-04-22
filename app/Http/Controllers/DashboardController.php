<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DocumentUpload;
use App\Models\Requirement;
use App\Models\Task;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use App\Models\PaymentProof;


class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();
    
    // Generate tasks automatically based on user's status
    $this->generateAutoTasks($user);
    
    // Calculate Profile Progress
    $profileFields = [
        'first_name', 'last_name', 'email', 'birthdate', 'sex', 
        'degree_program', 'permanent_address', 'current_address'
    ];
    
    $filledCount = 0;
    foreach ($profileFields as $field) {
        if (!empty($user->$field)) {
            $filledCount++;
        }
    }
    $profileProgress = round(($filledCount / count($profileFields)) * 100);
    
    // Calculate Documents Progress
    $totalRequirements = Requirement::count();
    $uploadedDocs = DocumentUpload::where('user_id', $user->id)->count();
    $documentsProgress = $totalRequirements > 0 ? round(($uploadedDocs / $totalRequirements) * 100) : 0;
    
    // Calculate Application Progress based on status steps
    $statusSteps = [
        'application_status' => ['approved', 'completed'],
        'document_status' => ['verified', 'approved', 'completed'],
        'interview_status' => ['completed', 'scheduled'],
        'payment_status' => ['paid', 'completed'],
        'final_status' => ['approved', 'completed']
    ];
    
    $completedSteps = 0;
    foreach ($statusSteps as $statusField => $completedValues) {
        $userStatus = $user->$statusField ?? 'pending';
        if (in_array(strtolower($userStatus), $completedValues)) {
            $completedSteps++;
        }
    }
    $applicationProgress = round(($completedSteps / count($statusSteps)) * 100);
    
    // Get pending tasks
    $tasks = Task::where('user_id', $user->id)
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Get unread messages count
    $unreadMessagesCount = Message::where('receiver_id', $user->id)
                                  ->where('is_read', false)
                                  ->count();
    
    // Get recent messages
    $recentMessages = Message::where('receiver_id', $user->id)
        ->with('sender')
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    // Fetch schedules
    $schedules = [];
    if (!empty($user->interview_date) && strtolower($user->interview_status) == 'scheduled') {
        $interviewDate = date('Y-m-d', strtotime($user->interview_date));
        $today = date('Y-m-d');
        
        if ($interviewDate >= $today) {
            $schedules[] = [
                'date' => $interviewDate,
                'title' => 'INTERVIEW',
                'location' => $user->interview_location ?? 'BU Open University',
                'time' => date('h:i A', strtotime($user->interview_time ?? '10:00 AM')),
                'type' => 'interview',
                'meeting_link' => $user->interview_meeting_link ?? null,
                'setup' => $user->interview_setup ?? 'onsite'
            ];
        }
    }
    
    // DITO ANG FIX: Inalis ang duplicate return at inayos ang compact()
    return view('applicant_dashboard', compact(
        'user', 
        'tasks', 
        'unreadMessagesCount', 
        'recentMessages',
        'profileProgress',
        'documentsProgress',
        'applicationProgress',
        'schedules'
    ));
}
    
    private function generateAutoTasks($user)
    {
        // Task 1: Complete Profile if missing fields
        $missingFields = [];
        if (empty($user->birthdate)) $missingFields[] = 'Birthdate';
        if (empty($user->sex)) $missingFields[] = 'Sex';
        if (empty($user->degree_program)) $missingFields[] = 'Degree Program';
        if (empty($user->permanent_address)) $missingFields[] = 'Permanent Address';
        if (empty($user->current_address)) $missingFields[] = 'Current Address';
        
        if (!empty($missingFields)) {
            Task::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'profile'],
                [
                    'title' => 'Complete Profile Information',
                    'description' => 'Missing: ' . implode(', ', $missingFields),
                    'status' => 'pending',
                    'action_url' => route('applicant.profile')
                ]
            );
        } else {
            Task::where('user_id', $user->id)
                ->where('type', 'profile')
                ->where('status', 'pending')
                ->update(['status' => 'completed']);
        }
        
        // Task 2: Check for missing documents
        $requirements = Requirement::all();
        $uploadedDocs = DocumentUpload::where('user_id', $user->id)->pluck('requirement_id')->toArray();
        
        foreach ($requirements as $req) {
            if (!in_array($req->id, $uploadedDocs)) {
                Task::updateOrCreate(
                    ['user_id' => $user->id, 'type' => 'document', 'related_id' => $req->id],
                    [
                        'title' => 'Upload: ' . $req->name,
                        'description' => $req->note ?? 'Please upload this required document',
                        'status' => 'pending',
                        'action_url' => route('applicant.documents')
                    ]
                );
            } else {
                Task::where('user_id', $user->id)
                    ->where('type', 'document')
                    ->where('related_id', $req->id)
                    ->where('status', 'pending')
                    ->update(['status' => 'completed']);
            }
        }
        
        // Task 3: Check for rejected/incomplete documents that need re-upload
        $rejectedDocs = DocumentUpload::where('user_id', $user->id)
            ->whereIn('status', ['rejected', 'incomplete'])
            ->with('requirement')
            ->get();
        
        foreach ($rejectedDocs as $doc) {
            Task::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'reupload', 'related_id' => $doc->requirement_id],
                [
                    'title' => 'Re-upload: ' . $doc->requirement->name,
                    'description' => 'Reason: ' . ($doc->verification_reason ?? 'Please re-upload the correct document'),
                    'status' => 'pending',
                    'action_url' => route('applicant.documents')
                ]
            );
        }
    }
    
    public function getMessages()
{
    $user = auth()->user();
    
    // DO NOT mark as read here - this is the problem!
    // Just get the messages with their read status
    
    $messages = Message::where('receiver_id', $user->id)
        ->with('sender')
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Count unread messages without marking them as read
    $unreadCount = Message::where('receiver_id', $user->id)
        ->where('is_read', false)
        ->count();
    
    return response()->json([
        'success' => true,
        'messages' => $messages->map(function($msg) {
            return [
                'id' => $msg->id,
                'message' => $msg->message,
                'sender_name' => $msg->sender->first_name . ' ' . $msg->sender->last_name,
                'created_at' => $msg->created_at->toISOString(),
                'created_at_formatted' => $msg->created_at->format('M d, Y h:i A'),
                'is_read' => $msg->is_read
            ];
        }),
        'unread_count' => $unreadCount
    ]);
}
    
    public function getProgressData()
    {
        $user = auth()->user();
        
        // Calculate Profile Progress
        $profileFields = ['first_name', 'last_name', 'email', 'birthdate', 'sex', 'degree_program', 'permanent_address', 'current_address'];
        $filledCount = 0;
        foreach ($profileFields as $field) {
            if (!empty($user->$field)) {
                $filledCount++;
            }
        }
        $profileProgress = round(($filledCount / count($profileFields)) * 100);
        
        // Calculate Documents Progress
        $totalRequirements = Requirement::count();
        $uploadedDocs = DocumentUpload::where('user_id', $user->id)->count();
        $documentsProgress = $totalRequirements > 0 ? round(($uploadedDocs / $totalRequirements) * 100) : 0;
        
        // Calculate Application Progress
        $statusSteps = ['application_status', 'document_status', 'interview_status', 'payment_status', 'final_status'];
        $completedSteps = 0;
        $approvedStatuses = ['approved', 'completed', 'verified', 'paid', 'scheduled'];
        
        foreach ($statusSteps as $statusField) {
            $userStatus = strtolower($user->$statusField ?? 'pending');
            if (in_array($userStatus, $approvedStatuses)) {
                $completedSteps++;
            }
        }
        $applicationProgress = round(($completedSteps / count($statusSteps)) * 100);
        
        return response()->json([
            'success' => true,
            'profile_progress' => $profileProgress,
            'documents_progress' => $documentsProgress,
            'application_progress' => $applicationProgress,
            'profile_filled' => $filledCount,
            'profile_total' => count($profileFields),
            'documents_uploaded' => $uploadedDocs,
            'documents_total' => $totalRequirements,
            'steps_completed' => $completedSteps,
            'steps_total' => count($statusSteps)
        ]);
    }

    public function getActivities()
    {
        $user = auth()->user();
        $activities = [];
        
        // Get document upload activities
        $recentUploads = DocumentUpload::where('user_id', $user->id)
            ->with('requirement')
            ->latest()
            ->take(10)
            ->get();
        
        foreach ($recentUploads as $upload) {
            $activities[] = [
                'date' => $upload->created_at->format('M d, Y | h:i A'),
                'activity' => 'Uploaded: ' . $upload->requirement->name
            ];
        }
        
        // Get status update activities
        if ($user->application_status == 'approved') {
            $activities[] = [
                'date' => $user->updated_at->format('M d, Y | h:i A'),
                'activity' => 'Application has been approved'
            ];
        }
        
        if ($user->interview_status == 'scheduled') {
            $activities[] = [
                'date' => $user->updated_at->format('M d, Y | h:i A'),
                'activity' => 'Interview has been scheduled'
            ];
        }
        
        // Sort by date descending
        $activities = collect($activities)->sortByDesc('date')->take(10);
        
        return response()->json([
            'success' => true,
            'activities' => $activities->values()->all()
        ]);
    }

    public function downloadPaymentStub($id)
{
    if (auth()->id() != $id) {
        abort(403, 'Unauthorized access.');
    }
    
    $applicant = User::findOrFail($id);
    
    if (!$applicant->payment_reference) {
        $applicant->save();
    }
    
    $pdf = Pdf::loadView('pdf.payment_stub', [
        'applicant' => $applicant,
        'authorizedName' => 'Sanny Shine F. Zoilo'
    ]);
    
    // Set custom paper size (1/4 ng A4)
    $pdf->setPaper([0, 0, 300, 420], 'portrait'); // width, height in points
    
    return $pdf->stream('Payment_Stub_' . $applicant->last_name . '.pdf');
}
    

public function uploadPaymentProof(Request $request)
{
    try {
        $user = auth()->user();
        
        $validator = validator($request->all(), [
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        $file = $request->file('payment_proof');
        $filename = 'payment_proof_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment_proofs', $filename, 'public');
        
        // Save to user - use 'pending' (lowercase)
        $user->payment_proof = $path;
        $user->payment_proof_uploaded_at = now();
        $user->payment_status = 'pending'; // Make sure this is lowercase 'pending'
        $user->save();
        
        // Notify staff
        $staff = User::where('role', 'staff')->first();
        if ($staff) {
            Message::create([
                'sender_id' => $user->id,
                'receiver_id' => $staff->id,
                'message' => "Applicant {$user->first_name} {$user->last_name} has uploaded their payment proof. Please verify.",
                'is_read' => false
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Payment proof uploaded! Waiting for staff verification.'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to upload: ' . $e->getMessage()
        ], 500);
    }
}

// Add method to get payment proof for preview
public function getPaymentProof()
{
    $user = auth()->user();
    
    return response()->json([
        'success' => true,
        'has_proof' => !empty($user->payment_proof),
        'proof_url' => $user->payment_proof ? asset('storage/' . $user->payment_proof) : null,
        'uploaded_at' => $user->payment_proof_uploaded_at,
        'payment_status' => $user->payment_status
    ]);
}

public function checkOnsiteStatus()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'verified' => (bool) $user->onsite_verified,
            'pending_verification' => (bool) $user->onsite_verification_pending
        ]);
    }

    /**
     * Request onsite verification
     * Student clicks "Already Submitted Onsite" button
     */
    public function requestOnsiteVerification(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Check if already verified
            if ($user->onsite_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your documents are already verified onsite.'
                ], 400);
            }
            
            // Check if already pending
            if ($user->onsite_verification_pending) {
                return response()->json([
                    'success' => false,
                    'message' => 'Verification already requested. Please wait for staff confirmation.'
                ], 400);
            }
            
            // Update user - set pending verification
            $user->onsite_verification_pending = true;
            $user->save();
            
            // Notify staff (optional - if you want to notify)
            $staff = User::where('role', 'staff')->first();
            if ($staff) {
                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $staff->id,
                    'message' => "Applicant {$user->first_name} {$user->last_name} has requested onsite submission verification.",
                    'is_read' => false
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Onsite verification requested successfully. Please wait for staff confirmation.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Request failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveUpload(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,txt|max:10240',
                'requirement_id' => 'required|exists:requirements,id'
            ]);
            
            $user = Auth::user();
            $requirementId = $request->requirement_id;
            $isReupload = $request->has('is_reuploaded') && $request->is_reuploaded == 'true';
            
            // Check if document exists and is approved
            $existingUpload = DocumentUpload::where('user_id', $user->id)
                ->where('requirement_id', $requirementId)
                ->first();
                
            // If document is approved, cannot modify
            if ($existingUpload && $existingUpload->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'This document is already approved and cannot be modified.'
                ], 403);
            }
            
            // Handle file upload
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            
            // Create directory if not exists
            $directory = 'documents/' . $user->id;
            $filename = $requirementId . '_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $path = $file->storeAs($directory, $filename, 'public');
            
            $uploadData = [
                'user_id' => $user->id,
                'requirement_id' => $requirementId,
                'file_path' => $path,
                'original_filename' => $originalName,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
            
            if ($existingUpload) {
                // Delete old file
                if (Storage::disk('public')->exists($existingUpload->file_path)) {
                    Storage::disk('public')->delete($existingUpload->file_path);
                }
                
                // Update existing - reset status to pending
                $existingUpload->update(array_merge($uploadData, [
                    'status' => 'pending',
                    'verification_reason' => null,
                    'verified_by' => null,
                    'verified_at' => null
                ]));
                $upload = $existingUpload;
            } else {
                $uploadData['status'] = 'pending';
                $upload = DocumentUpload::create($uploadData);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file_path' => Storage::url($path),
                'path' => $path,
                'upload_id' => $upload->id
            ]);

            
            
        } catch (\Exception $e) {
            \Log::error('Upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove uploaded document
     */
    public function removeUpload(Request $request)
    {
        try {
            $request->validate([
                'requirement_id' => 'required|exists:requirements,id'
            ]);
            
            $user = Auth::user();
            
            $upload = DocumentUpload::where('user_id', $user->id)
                ->where('requirement_id', $request->requirement_id)
                ->first();
                
            if (!$upload) {
                return response()->json([
                    'success' => false,
                    'message' => 'No upload found for this document'
                ], 404);
            }
            
            if ($upload->status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete an approved document'
                ], 403);
            }
            
            // Delete file
            if (Storage::disk('public')->exists($upload->file_path)) {
                Storage::disk('public')->delete($upload->file_path);
            }
            
            $upload->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'File removed successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Remove failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
 * Delete selected messages
 */
public function deleteMessages(Request $request)
{
    try {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'integer|exists:messages,id'
        ]);
        
        $user = auth()->user();
        
        // Delete only messages that belong to this user (as receiver)
        $deletedCount = Message::where('receiver_id', $user->id)
            ->whereIn('id', $request->message_ids)
            ->delete();
        
        if ($deletedCount > 0) {
            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' message(s) deleted successfully',
                'deleted_count' => $deletedCount
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No messages were deleted'
            ], 400);
        }
        
    } catch (\Exception $e) {
        \Log::error('Delete messages error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete messages: ' . $e->getMessage()
        ], 500);
    }
}

public function markAsRead()
{
    try {
        $user = auth()->user();
        
        // Update all unread messages for this user
        $updatedCount = Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'message' => $updatedCount . ' message(s) marked as read',
            'updated_count' => $updatedCount
        ]);
    } catch (\Exception $e) {
        \Log::error('Mark as read error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

}