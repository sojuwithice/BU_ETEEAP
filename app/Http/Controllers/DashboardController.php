<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DocumentUpload;
use App\Models\Requirement;
use App\Models\Task;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Generate tasks automatically based on user's status
        $this->generateAutoTasks($user);
        
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
        
        return view('applicant_dashboard', compact('user', 'tasks', 'unreadMessagesCount', 'recentMessages'));
    }
    
    private function generateAutoTasks($user)
    {
        // Task 1: Complete Profile if missing fields
        $missingFields = [];
        if (empty($user->birthdate)) $missingFields[] = 'Birthdate';
        if (empty($user->sex)) $missingFields[] = 'Sex';
        if (empty($user->degree_program)) $missingFields[] = 'Degree Program';
        if (empty($user->permanent_address)) $missingFields[] = 'Permanent Address';
        
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
                'created_at' => $msg->created_at->toISOString(), // Use ISO string for better parsing
                'created_at_formatted' => $msg->created_at->format('M d, Y h:i A'),
                'is_read' => $msg->is_read
            ];
        }),
        'unread_count' => Message::where('receiver_id', $user->id)->where('is_read', false)->count()
    ]);
}
}