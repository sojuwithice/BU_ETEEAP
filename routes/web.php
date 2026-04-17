<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController; 
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('register');

// ACTIONS
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED
// PROTECTED ROUTES
Route::middleware(['auth'])->group(function () {
    // Siguraduhin na ang DashboardController ay may index method
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('applicant.dashboard');
    
    Route::get('/staff_dash', function () {
        return view('staff_dash');
    })->name('staff.dashboard');

    // Iba pang applicant routes sa loob ng middleware
    Route::get('/profile', function () {
        return view('applicant_profile');
    })->name('applicant.profile');

    Route::get('/applicant-docs', function () {
        return view('applicant_documents');
    })->name('applicant.documents');

    Route::get('/info', function () {
        return view('applicant_info');
    })->name('applicant.info');
});

Route::post('/update-password', [AuthController::class, 'updatePassword'])
    ->middleware('auth');

Route::post('/profile/upload-image', [ProfileController::class, 'uploadImage'])->name('profile.upload.image');








Route::get('/profile', function () {
    return view('applicant_profile');
})->name('applicant.profile');

Route::get('/applicant-docs', function () {
    return view('applicant_documents');
})->name('applicant.documents');


Route::get('/info', function () {
    return view('applicant_info');
})->name('applicant.info');

Route::get('/verify-documents', function () {
    return view('document_verification');
})->name('document.verification');