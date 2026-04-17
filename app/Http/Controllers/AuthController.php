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

        // ================= CREATE USER =================
        // AuthController.php sa loob ng register()
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password), // Hashed (One-way)
            'password_plain' => $request->password,         // Encrypted (Two-way, safe for Privacy)
            'role'       => $request->role
        ]);

        // Para lumitaw agad kahit hindi pa nag-reresfresh ang auth cache
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

    /** @var \App\Models\User $user */
    $user = Auth::user();
    
    // I-update ang model fields
    $user->password = Hash::make($request->password);
    $user->password_plain = $request->password; 
    $user->save();

    // ETO ANG FIX: I-update ang session para sa dashboard display
    session(['raw_password' => $request->password]);

    return response()->json([
        'message' => 'Password updated successfully!'
    ]);
}
}