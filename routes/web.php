<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ApplicantDocumentController;
use App\Http\Controllers\StaffDashboardController;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () { return view('landing'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('applicant.dashboard');
    
    Route::get('/staff_dash', [StaffDashboardController::class, 'index'])->name('staff.dashboard');

    Route::get('/profile', function () {
        return view('applicant_profile');
    })->name('applicant.profile');

    Route::get('/info', function () {
        return view('applicant_info');
    })->name('applicant.info');

    // Documents routes (STUDENT SIDE for apply documents)
    Route::get('/applicant-docs', [ApplicantDocumentController::class, 'index'])->name('applicant.documents');
    Route::post('/applicant/upload/save', [ApplicantDocumentController::class, 'storeUpload'])->name('applicant.upload.save');
    Route::post('/applicant/documents/remove', [ApplicantDocumentController::class, 'destroyUpload'])->name('applicant.upload.remove');

    // Onsite verification routes
    Route::post('/applicant/onsite/request', [ApplicantDocumentController::class, 'requestOnsiteVerification'])->name('applicant.onsite.request');
    Route::get('/applicant/onsite/status', [ApplicantDocumentController::class, 'getOnsiteStatus'])->name('applicant.onsite.status');
    Route::post('/staff/confirm/onsite', [ApplicantDocumentController::class, 'confirmOnsiteSubmission'])->name('staff.confirm.onsite');

    // Requirements routes (STAFF SIDE - requirements management)
    Route::get('/requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::post('/requirements', [RequirementController::class, 'store']);
    Route::put('/requirements/{id}', [RequirementController::class, 'update']);
    Route::delete('/requirements/{id}', [RequirementController::class, 'destroy']);
    
    // Staff applicant routes (STAFF SIDE - pag-manage ng applicants)
    Route::get('/staff/applicant/{id}', [StaffDashboardController::class, 'getApplicantDetails'])->name('staff.applicant.details');
    Route::post('/staff/applicant/{id}/status', [StaffDashboardController::class, 'updateApplicantStatus'])->name('staff.applicant.status');
    Route::get('/staff/applicant/{id}/info', [StaffDashboardController::class, 'viewApplicantInfo'])->name('staff.applicant.info');
    Route::get('/staff/applicant/{id}/documents', [StaffDashboardController::class, 'viewApplicantDocuments'])->name('staff.applicant.documents');
    Route::get('/staff/applicant/{id}/document/{requirementId}', [StaffDashboardController::class, 'getDocumentDetails'])->name('staff.applicant.document.details');
    Route::post('/staff/applicant/{id}/document-verification', [StaffDashboardController::class, 'updateDocumentVerification'])->name('staff.applicant.document.verification');
    
    // Status update routes
    Route::post('/staff/applicant/{id}/application-status', [StaffDashboardController::class, 'updateApplicationStatus'])->name('staff.applicant.application.status');
    Route::post('/staff/applicant/{id}/document-status', [StaffDashboardController::class, 'updateDocumentStatus'])->name('staff.applicant.document.status');
    Route::post('/staff/applicant/{id}/payment-status', [StaffDashboardController::class, 'updatePaymentStatus'])->name('staff.applicant.payment.status');
    Route::post('/staff/applicant/{id}/final-status', [StaffDashboardController::class, 'updateFinalStatus'])->name('staff.applicant.final.status');

    // INTERVIEW ROUTES - FIXED: Changed from StaffController to StaffDashboardController
    // Interview routes - Using StaffDashboardController (not StaffController)
Route::post('/staff/applicant/{id}/interview', [StaffDashboardController::class, 'setInterview'])->name('staff.applicant.setInterview');
Route::post('/staff/applicant/{id}/reschedule-interview', [StaffDashboardController::class, 'rescheduleInterview'])->name('staff.applicant.rescheduleInterview');
Route::post('/staff/applicant/{id}/cancel-interview', [StaffDashboardController::class, 'cancelInterview'])->name('staff.applicant.cancelInterview');

Route::post('/staff/applicant/{id}/interview-result', [StaffDashboardController::class, 'updateInterviewResult'])->name('staff.applicant.interview-result');

    Route::post('/staff/applicant/{id}/message', [StaffDashboardController::class, 'sendMessage'])->name('staff.applicant.message');

    Route::post('/staff/applicant/{id}/send-payment-stub', [StaffDashboardController::class, 'sendPaymentStub'])->name('staff.send-payment-stub');

    Route::get('/payment-stub/{id}', [PaymentController::class, 'generatePdf'])
    ->name('payment.stub');

// Student download payment stub - MAKE SURE THIS EXISTS
Route::get('/applicant/download-payment-stub/{id}', [DashboardController::class, 'downloadPaymentStub'])->name('applicant.download-payment-stub');

// Add these routes inside your auth middleware group
Route::post('/applicant/upload-payment-proof', [DashboardController::class, 'uploadPaymentProof'])->name('applicant.upload-payment-proof');

// Add route for this
Route::get('/staff/applicant/{id}/payment-proof-status', [StaffDashboardController::class, 'getPaymentProofStatus']);


// Add these routes
Route::post('/staff/applicant/{id}/verify-payment', [StaffDashboardController::class, 'verifyPayment'])->name('staff.verify-payment');
Route::get('/applicant/get-payment-proof', [DashboardController::class, 'getPaymentProof'])->name('applicant.get-payment-proof');
    
    // Password and profile routes
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload-image', [AuthController::class, 'uploadProfileImage'])->name('profile.upload.image');

    // Add these routes inside your auth middleware group
    Route::post('/task/{id}/complete', [DashboardController::class, 'completeTask'])->name('task.complete');
    Route::get('/applicant/messages', [DashboardController::class, 'getMessages'])->name('applicant.messages');
    Route::get('/applicant/progress', [DashboardController::class, 'getProgressData'])->name('applicant.progress');
    Route::get('/applicant/activities', [DashboardController::class, 'getActivities'])->name('applicant.activities');
});

Route::get('/verify-documents', function () {
    return view('document_verification');
})->name('document.verification');


Route::get('/payment-stub/{id}', function ($id) {

    // sample data (palitan mo kung may table ka)
    $data = [
        'id' => $id,
        'name' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        'amount' => '1,000.00',
        'date' => now()->format('F d, Y')
    ];

    $pdf = Pdf::loadView('pdf.payment_stub', $data);

    return $pdf->stream('payment_stub.pdf');
});

Route::get('/preview-payment-stub', function () {
    $applicant = Auth::user(); // or use a specific user ID
    // Or for testing specific applicant:
    // $applicant = User::find(5); // change to actual ID
    
    $pdf = Pdf::loadView('pdf.payment_stub', [
        'applicant' => $applicant,
        'authorizedName' => 'Sanny Shine F. Zoilo'
    ]);
    
    return $pdf->stream('payment_stub_preview.pdf');
})->middleware('auth');