<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
        public function index()
    {
        $user = Auth::user();

        return view('applicant_dashboard', [
            'firstName' => $user->first_name
        ]);
    }
}
