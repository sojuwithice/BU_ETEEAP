<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\AuditLogger;

class AdminUserController extends Controller
{
    // ============================================
    // LOAD PAGE
    // ============================================
    public function index()
    {
        $users = User::where('role', '!=', 'admin')
            ->latest()
            ->get();

        return view('user_management', compact('users'));
    }

    // ============================================
    // ADD USER
    // ============================================
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'password_plain' => $request->password,
            'failed_attempts' => 0,
            'is_suspended' => 0,
            'password_changed_at' => now() // FIXED
        ]);

            AuditLogger::log(
        'User Management',
        'Created user',
        "Created user: {$user->email} with role: {$user->role}"
    );

        return response()->json([
            'success' => true,
            'message' => 'User added successfully.'
        ]);
    }

    // ============================================
    // RESET PASSWORD
    // ============================================
    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        $defaultPassword = 'Password123';

        $user->password = Hash::make($defaultPassword);
        $user->password_plain = $defaultPassword;
        $user->password_changed_at = now(); // FIXED
        $user->failed_attempts = 0;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully.',
            'new_password' => $defaultPassword
        ]);
    }

    // ============================================
    // TOGGLE STATUS
    // ============================================
    public function toggleStatus($id)
{
    $user = User::findOrFail($id);

    $user->is_suspended = !$user->is_suspended;

    $user->save();

    // AUDIT LOG
    AuditLogger::log(
        'Security',
        $user->is_suspended ? 'Suspended account' : 'Reactivated account',
        ($user->is_suspended ? 'Suspended' : 'Reactivated') . " account: {$user->email}"
    );

    return response()->json([
        'success' => true,
        'status' => $user->is_suspended
    ]);
}

    // ============================================
// DELETE USER
// ============================================
public function destroy($id)
{
    $user = User::findOrFail($id);
    $userEmail = $user->email; // <- I-ADD TO (nawala to)

    if ($user->profile_image) {
        Storage::disk('public')->delete($user->profile_image);
    }

    $user->delete();

    // ADD AUDIT LOG
    AuditLogger::log(
        'User Management',
        'Deleted user',
        "Deleted user: {$userEmail}" // <- ITO GAMITIN
    );

    return response()->json([
        'success' => true,
        'message' => 'User deleted successfully.'
    ]);
}

    // ============================================
    // UPDATE PASSWORD (MAIN FIX HERE)
    // ============================================
    public function updatePassword(Request $request, $id)
{
    $request->validate([
        'password' => 'required|min:8|confirmed',
    ]);

    try {
        $user = User::findOrFail($id);

        $user->password = Hash::make($request->password);
        $user->password_plain = $request->password; // optional
        $user->password_changed_at = now();
        $user->failed_attempts = 0;

        $user->save();

        // ADD AUDIT LOG
            AuditLogger::log(
                'Security',
                'Updated password',
                "Updated password for user: {$user->email}"
            );

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!',
            'changed_at' => Carbon::parse($user->password_changed_at)
                ->format('F d, Y h:i A') // WITH TIME
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    // ============================================
    // PROFILE IMAGE
    // ============================================
    public function uploadProfile(Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);

        $image = $request->image;

        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);

        $imageName = time() . '.png';

        Storage::disk('public')->put(
            'profile_images/' . $imageName,
            base64_decode($image)
        );

        $user = Auth::user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $user->profile_image = 'profile_images/' . $imageName;

        $user->save();

        // ADD AUDIT LOG
        AuditLogger::log(
            'User Management',
            'Updated profile image',
            "Updated profile image for: {$user->email}"
        );

        return response()->json([
            'success' => true,
            'image' => asset('storage/profile_images/' . $imageName)
        ]);
    }

    // ============================================
    // ADMIN PASSWORD UPDATE (optional secure)
    // ============================================
    public function updateAdminPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 422);
        }

        $request->validate([
            'password' => 'required|min:6|confirmed'
        ]);

        $user->password = Hash::make($request->password);
        $user->password_plain = $request->password;
        $user->password_changed_at = now(); // FIXED
        $user->failed_attempts = 0;

        $user->save();

        AuditLogger::log(
            'Security',
            'Changed own password',
            "Changed password for account: {$user->email}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }
}