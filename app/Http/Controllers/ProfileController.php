<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function uploadImage(Request $request)
    {
        $user = Auth::user();
        $image = $request->image; // base64 encoded string

        // Alisin ang data:image/jpeg;base64, part
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        
        // Pangalan ng file
        $imageName = 'profile_' . $user->id . '_' . time() . '.jpg';

        try {
            // I-save sa storage/app/public/profile_pictures
            Storage::disk('public')->put('profile_pictures/' . $imageName, base64_decode($image));

            // Burahin ang lumang image kung meron man para hindi puno ang server
            if ($user->profile_image && $user->profile_image !== 'default-profile.png') {
                Storage::disk('public')->delete($user->profile_image);
            }

            // I-update ang database column
            $user->profile_image = 'profile_pictures/' . $imageName;
            $user->save();

            return response()->json(['success' => true, 'path' => asset('storage/' . $user->profile_image)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
