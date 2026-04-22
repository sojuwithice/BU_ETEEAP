<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

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
            'password'   => Hash::make($request->password), // Hashed (One-way)
            'password_plain' => $request->password,         // Encrypted (Two-way, safe for Privacy)
            'role'       => $request->role
        ]);

    
        session(['raw_password' => $request->password]);

        Auth::login($user);

        return $this->redirectRole($user);
    }

    // ================= LOGIN =================
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

            $credentials = $request->only('email', 'password', 'role');

            if (Auth::attempt($credentials)) {
                return $this->redirectRole(Auth::user());
            }

            return back()
                ->withErrors([
                    'email' => 'Invalid credentials or role mismatch'
                ], 'login')
                ->withInput();
        }

    // ================= ROLE REDIRECT =================
    private function redirectRole($user)
    {
        return $user->role === 'staff'
            ? redirect()->route('staff.dashboard')
            : redirect()->route('applicant.dashboard');
    }

    // ================= LOGOUT =================
    public function logout()
    {
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
    $user->save();

    session(['raw_password' => $request->password]);

    return response()->json([
        'message' => 'Password updated successfully!'
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

        return back()->with('success', 'Profile updated successfully!');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to update profile.');
    }
}

public function uploadProfileImage(Request $request)
{
    $request->validate([
        'image' => 'required'
    ]);

    $user = auth()->user();

    $image = $request->image;
    $image = str_replace('data:image/jpeg;base64,', '', $image);
    $image = str_replace(' ', '+', $image);

    $fileName = 'profile_' . $user->id . '_' . time() . '.jpg';

    \Storage::disk('public')->put(
        'profile_images/' . $fileName,
        base64_decode($image)
    );

    // save sa DB
    $user->profile_image = 'profile_images/' . $fileName;
    $user->save();

    return response()->json([
        'success' => true,
        'path' => asset('storage/profile_images/' . $fileName)
    ]);
}
}