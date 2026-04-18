<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Requirement;  
use App\Models\DocumentUpload;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get all users with role 'applicant' or 'student'
        $query = User::where('role', 'applicant')
            ->orWhere('role', 'student')
            ->orderBy('created_at', 'desc');
        
        // Search functionality
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
        
        // Get additional data for each applicant
        foreach ($applicants as $applicant) {
            // Get latest document upload date
            $latestUpload = DocumentUpload::where('user_id', $applicant->id)
                ->latest()
                ->first();
            $applicant->last_update = $latestUpload ? $latestUpload->created_at : $applicant->updated_at;
            
            // Get document upload count
            $applicant->upload_count = DocumentUpload::where('user_id', $applicant->id)->count();
            
            // Determine status based on document submissions
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
        
        // Format birthdate properly - use 'birthdate' not 'birth_date'
        $birthdate = 'Not specified';
        if ($applicant->birthdate && $applicant->birthdate != '0000-00-00') {
            try {
                $birthdate = date('F d, Y', strtotime($applicant->birthdate));
            } catch (\Exception $e) {
                $birthdate = $applicant->birthdate;
            }
        }
        
        // Get document uploads with requirement names
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
        $documents = DocumentUpload::where('user_id', $id)->with('requirement')->get();
        $requirements = Requirement::all();
        
        return view('applicant_documents', compact('applicant', 'documents', 'requirements'));
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
        // Store message logic here - you may need to create a Message model
        // For now, just return success
        return response()->json(['success' => true, 'message' => 'Message sent successfully']);
    }
}