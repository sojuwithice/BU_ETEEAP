<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomepageSetting;

class LandingController extends Controller
{
    public function index()
    {
        // Just get the settings from database, or null if none exists
        $home = HomepageSetting::first();
        
        // If no settings exist, just pass an empty model instance (all fields null)
        if (!$home) {
            $home = new HomepageSetting();
        }
        
        return view('landing', compact('home'));
    }
}