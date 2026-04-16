<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController; 

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
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        return view('applicant_dashboard');
    })->name('applicant.dashboard');

    Route::get('/staff_dash', function () {
        return view('staff_dash');
    })->name('staff.dashboard');

});

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