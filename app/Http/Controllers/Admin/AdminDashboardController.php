<?php
// app/Http/Controllers/Admin/AdminDashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin');
    }
    
    public function getStats()
    {
        try {
            // Total Registrations - STUDENT role
            $totalRegistrations = User::where('role', 'student')->count();
            
            // Approved Students
            $approvedCount = User::where('role', 'student')
                ->where('application_status', 'approved')
                ->count();
            
            // Pending Status
            $pendingCount = User::where('role', 'student')
                ->whereIn('application_status', ['pending', 'submitted', 'under_review'])
                ->count();
            
            // Program Statistics
            $programCounts = [
                'BS Computer Science' => User::where('role', 'student')->where('degree_program', 'BS Computer Science')->count(),
                'BS Fisheries' => User::where('role', 'student')->where('degree_program', 'BS Fisheries')->count(),
                'BS Nursing' => User::where('role', 'student')->where('degree_program', 'BS Nursing')->count(),
                'BS Automotive Technology' => User::where('role', 'student')->where('degree_program', 'BS Automotive Technology')->count(),
                'AB Communication' => User::where('role', 'student')->where('degree_program', 'AB Communication')->count(),
            ];
            
            // Monthly Applications (Last 6 months)
            $monthlyLabels = [];
            $monthlyData = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyLabels[] = $date->format('M');
                
                $count = User::where('role', 'student')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                
                $monthlyData[] = $count;
            }
            
            // Cumulative Trend
            $trendData = [];
            $cumulative = 0;
            
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthlyCount = User::where('role', 'student')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
                
                $cumulative += $monthlyCount;
                $trendData[] = $cumulative;
            }
            
            // System Health
            $totalUsers = User::count();
            $activeUsersLast30Days = User::where('updated_at', '>=', Carbon::now()->subDays(30))->count();
            $sysHealth = $totalUsers > 0 ? round(($activeUsersLast30Days / $totalUsers) * 100) : 100;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'totalRegistrations' => $totalRegistrations,
                    'approvedCount' => $approvedCount,
                    'pendingCount' => $pendingCount,
                    'systemHealth' => $sysHealth,
                    'programCounts' => $programCounts,
                    'monthlyLabels' => $monthlyLabels,
                    'monthlyData' => $monthlyData,
                    'trendData' => $trendData,
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}