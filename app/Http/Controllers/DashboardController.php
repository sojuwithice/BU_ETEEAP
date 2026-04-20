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
        
        // Fetch schedules from user's interview data - FIXED with correct column names
        // Fetch schedules from user's interview data - FIXED with correct column names
$schedules = [];

// Check if user has interview scheduled (using correct column names: interview_date, interview_time, interview_location)
if (!empty($user->interview_date) && strtolower($user->interview_status) == 'scheduled') {
    // Check if interview date is today or future
    $interviewDate = date('Y-m-d', strtotime($user->interview_date));
    $today = date('Y-m-d');
    
    if ($interviewDate >= $today) {
        $schedules[] = [
            'date' => $interviewDate,
            'title' => 'INTERVIEW',
            'location' => $user->interview_location ?? 'BU Open University',
            'time' => date('h:i A', strtotime($user->interview_time ?? '10:00 AM')),
            'type' => 'interview',
            'meeting_link' => $user->interview_meeting_link ?? null,  // ADD THIS
            'setup' => $user->interview_setup ?? 'onsite'  // ADD THIS
        ];
    }
}
        
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
        
        // Mark all messages as read
        Message::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        $messages = Message::where('receiver_id', $user->id)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->get();
        
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
            'unread_count' => Message::where('receiver_id', $user->id)->where('is_read', false)->count()
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
    // Make sure the logged-in user is downloading their own stub
    if (auth()->id() != $id) {
        abort(403, 'Unauthorized access.');
    }
    
    $applicant = User::findOrFail($id);
    
    // Generate reference if wala
    if (!$applicant->payment_reference) {
        $applicant->save();
    }
    
    // Load view with data
    $pdf = Pdf::loadView('pdf.payment_stub', ['applicant' => $applicant]);
    
    // Download the PDF
    return $pdf->download('Payment_Stub_' . $applicant->last_name . '.pdf');
}
    
}