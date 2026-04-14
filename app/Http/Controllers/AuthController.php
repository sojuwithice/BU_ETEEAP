<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        // I-load ang login.blade.php
        return view('login'); 
    }

    public function showSignup()
    {
        // Dahil iisang page lang sila, i-load pa rin ang login.blade.php
        // Pero magpapasa tayo ng flag para alam ng JS na dapat mag-slide
        return view('login');
    }
}