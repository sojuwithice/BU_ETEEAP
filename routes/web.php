<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ApplicantDocumentController;
use App\Http\Controllers\StaffDashboardController;

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
    Route::post('/staff/applicant/{id}/interview', [StaffDashboardController::class, 'setInterview'])->name('staff.applicant.interview');
    Route::post('/staff/applicant/{id}/message', [StaffDashboardController::class, 'sendMessage'])->name('staff.applicant.message');
    
    // Password and profile routes
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload-image', [AuthController::class, 'uploadProfileImage'])->name('profile.upload.image');

    // Add these routes inside your auth middleware group
Route::post('/task/{id}/complete', [DashboardController::class, 'completeTask'])->name('task.complete');
Route::get('/applicant/messages', [DashboardController::class, 'getMessages'])->name('applicant.messages');

Route::get('/applicant/progress', [DashboardController::class, 'getProgressData'])->name('applicant.progress');

});

Route::get('/verify-documents', function () {
    return view('document_verification');
})->name('document.verification');