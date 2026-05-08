<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Helpers\AuditLogger;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login');
    }

    public function showSignup() {
        return view('login');
    }

    // ================= REGISTER =================
public function register(Request $request)
{
    $validator = validator($request->all(), [
        'first_name' => 'required',
        'last_name'  => 'required',
        'email'      => 'required|email|unique:users',
        'password'   => 'required|min:6|confirmed',
        'role'       => 'required'
    ], [
        'first_name.required' => 'First name is required.',
        'last_name.required'  => 'Last name is required.',
        'email.required'      => 'Email is required.',
        'email.email'         => 'Enter a valid email address.',
        'email.unique'        => 'This email is already registered.',
        'password.required'   => 'Password is required.',
        'password.min'        => 'Password must be at least 6 characters.',
        'password.confirmed'  => 'Passwords do not match.',
        'role.required'       => 'Please select a role.'
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator, 'signup')
            ->withInput();
    }

        // ================= RECAPTCHA CHECK =================
/*
$verify = Http::asForm()->post(
    'https://www.google.com/recaptcha/api/siteverify',
    [
        'secret' => env('RECAPTCHA_SECRET'),
        'response' => $request->recaptcha_token
    ]
);

if (!($verify['success'] ?? false)) {
    return back()
        ->withErrors([
            'recaptcha' => 'Please verify that you are human.'
        ], 'signup')
        ->withInput();
}
*/

        // ================= CREATE USER =================
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'password_plain' => $request->password,
            'role'       => $request->role,

            'failed_attempts' => 0,
            'is_suspended' => 0,
            'password_changed_at' => now(),
        ]);

         $user->last_login_at = now();
        $user->save();

        // ADD AUDIT LOG
        AuditLogger::log(
            'User Management',
            'Registered new account',
            "New registration: {$user->email} as {$user->role}"
        );
    
        session(['raw_password' => $request->password]);

        Auth::login($user);

        return $this->redirectRole($user);
    }

public function login(Request $request)
{
    $validator = validator($request->all(), [
        'email'    => 'required|email',
        'password' => 'required',
        'role'     => 'required'
    ]);

    if ($validator->fails()) {
        return back()
        ->withErrors($validator, 'login')
        ->withInput();
    }

    // ============================================
    // FIND USER
    // ============================================

    $user = User::where('email', $request->email)->first();

    // ============================================
    // USER NOT FOUND
    // ============================================

    if (!$user) {

        return back()
        ->withErrors([
            'email' => 'Invalid credentials'
        ], 'login')
        ->withInput();
    }

    // ============================================
    // ACCOUNT SUSPENDED
    // ============================================

    if ($user->is_suspended) {

        return back()
        ->withErrors([
            'email' => 'This account has been suspended.'
        ], 'login')
        ->withInput();
    }

    // ============================================
    // CHECK PASSWORD
    // ============================================

    if (!Hash::check($request->password, $user->password)) {

        // FAILED ATTEMPTS +1
        $user->increment('failed_attempts');

        // AUTO SUSPEND AFTER 5 ATTEMPTS
        if ($user->failed_attempts >= 5) {

            $user->is_suspended = true;
            $user->save();

            AuditLogger::log(
                    'Security',
                    'Account suspended',
                    "Account suspended due to failed attempts: {$user->email}"
                );

            return back()
            ->withErrors([
                'email' => 'Account suspended after too many failed attempts.'
            ], 'login')
            ->withInput();
        }

        return back()
        ->withErrors([
            'email' => 'Invalid credentials'
        ], 'login')
        ->withInput();
    }

    // ============================================
    // LOGIN SUCCESS
    // ============================================

    Auth::login($user);

    // RESET FAILED ATTEMPTS
    $user->failed_attempts = 0;

    // SAVE LAST LOGIN
    $user->last_login_at = now();

    $user->save();

    AuditLogger::log(
            'Security',
            'Login successful',
            "User logged in: {$user->email}"
        );

    // ============================================
    // ROLE CHECK
    // ============================================

    if ($request->role === 'staff') {

        if ($user->role === 'staff' || $user->role === 'admin') {

            return $this->redirectRole($user);
        }
    }

    if ($request->role === 'student') {

        if ($user->role === 'student') {

            return $this->redirectRole($user);
        }
    }

    Auth::logout();

    return back()
    ->withErrors([
        'email' => 'Role mismatch. Please select the correct role.'
    ], 'login')
    ->withInput();
}

        private function redirectRole($user)
    {
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        if ($user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }

        return redirect()->route('applicant.dashboard');
    }

    // ================= LOGOUT =================
    public function logout()
    {
        // ADD AUDIT LOG BEFORE LOGOUT
        if (Auth::check()) {
            AuditLogger::log(
                'Security',
                'Logout',
                "User logged out: " . Auth::user()->email
            );
        }

        Auth::logout();
        return redirect('/login');
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $user = Auth::user();

    $user->password = Hash::make($request->password);
    $user->password_plain = $request->password;

    
    $user->password_changed_at = now();

    $user->save();

    // ADD AUDIT LOG
        AuditLogger::log(
            'Security',
            'Changed password',
            "Changed password for account: {$user->email}"
        );


    session(['raw_password' => $request->password]);

    return response()->json([
        'message' => 'Password updated successfully!',
        'changed_at' => $user->password_changed_at->format('F d, Y h:i A')
    ]);
}

public function updateProfile(Request $request)
{
    try {
        $user = auth()->user();

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'extension_name' => $request->extension_name,
            'birthdate' => $request->birthdate,
            'sex' => $request->sex,
            'email' => $request->email,
            'degree_program' => $request->degree_program,
            'permanent_address' => $request->permanent_address,
            'current_address' => $request->current_address,
        ]);

        // ADD AUDIT LOG
            AuditLogger::log(
                'User Management',
                'Updated profile',
                "Updated profile information for: {$user->email}"
            );

        return back()->with('success', 'Profile updated successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to update profile.');
    }
}

public function uploadProfileImage(Request $request)
{
    try {
        $user = auth()->user();
        
        // OPTION 1: File upload via FormData (from croppie blob)
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            
            // Validate file
            $validator = validator($request->all(), [
                'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image file. Please upload JPG or PNG (max 2MB)'
                ], 422);
            }
            
            // Delete old image if exists
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }
            
            // Generate unique filename
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store the image
            $path = $file->storeAs('profile_images', $fileName, 'public');
            
            // Update user record
            $user->profile_image = $path;
            $user->save();

            // ADD AUDIT LOG
                AuditLogger::log(
                    'User Management',
                    'Uploaded profile image',
                    "Uploaded new profile image for: {$user->email}"
                );

            
            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully!',
                'path' => \Storage::url($path)
            ]);
        }
        
        // OPTION 2: Base64 image (from croppie result)
        if ($request->has('image')) {
            $image = $request->image;
            
            // Remove base64 prefix if present
            if (str_contains($image, 'base64,')) {
                $image = explode('base64,', $image)[1];
            }
            
            $image = str_replace(' ', '+', $image);
            $decodedImage = base64_decode($image);
            
            if ($decodedImage === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid image data'
                ], 422);
            }
            
            // Delete old image
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }
            
            $fileName = 'profile_' . $user->id . '_' . time() . '.jpg';
            \Storage::disk('public')->put('profile_images/' . $fileName, $decodedImage);
            
            $user->profile_image = 'profile_images/' . $fileName;
            $user->save();

            // ADD AUDIT LOG
                AuditLogger::log(
                    'User Management',
                    'Uploaded profile image',
                    "Uploaded new profile image for: {$user->email}"
                );
            
            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully!',
                'path' => asset('storage/profile_images/' . $fileName)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'No image file provided'
        ], 400);
        
    } catch (\Exception $e) {
        \Log::error('Profile upload error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to upload image: ' . $e->getMessage()
        ], 500);
    }
}
}