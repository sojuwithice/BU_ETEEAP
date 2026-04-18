<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\ApplicantDocumentController;

Route::get('/', function () { return view('landing'); });

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('applicant.dashboard');
    
    Route::get('/staff_dash', function () {
        return view('staff_dash');
    })->name('staff.dashboard');

    Route::get('/profile', function () {
        return view('applicant_profile');
    })->name('applicant.profile');

    Route::get('/info', function () {
        return view('applicant_info');
    })->name('applicant.info');

    // DITO DAPAT ANG ROUTE MO PARA SA DOCUMENTS
    Route::get('/applicant-docs', [ApplicantDocumentController::class, 'index'])->name('applicant.documents');
    Route::post('/applicant/upload/save', [ApplicantDocumentController::class, 'storeUpload'])->name('applicant.upload.save');
    Route::post('/applicant/documents/remove', [ApplicantDocumentController::class, 'destroyUpload'])->name('applicant.upload.remove');

    // ================= ONSITE VERIFICATION ROUTES =================
    Route::post('/applicant/onsite/request', [ApplicantDocumentController::class, 'requestOnsiteVerification'])->name('applicant.onsite.request');
    Route::get('/applicant/onsite/status', [ApplicantDocumentController::class, 'getOnsiteStatus'])->name('applicant.onsite.status');
    Route::post('/staff/confirm/onsite', [ApplicantDocumentController::class, 'confirmOnsiteSubmission'])->name('staff.confirm.onsite');

    // Other staff-side requirements
    Route::get('/requirements', [RequirementController::class, 'index'])->name('requirements.index');
    Route::post('/requirements', [RequirementController::class, 'store']);
    Route::put('/requirements/{id}', [RequirementController::class, 'update']);
    Route::delete('/requirements/{id}', [RequirementController::class, 'destroy']);
    
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/upload-image', [AuthController::class, 'uploadProfileImage'])->name('profile.upload.image');
});










Route::get('/profile', function () {
    return view('applicant_profile');
})->name('applicant.profile');



Route::get('/info', function () {
    return view('applicant_info');
})->name('applicant.info');

Route::get('/verify-documents', function () {
    return view('document_verification');
})->name('document.verification');


