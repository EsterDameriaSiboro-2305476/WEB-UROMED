<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\UrineTest;
use App\Models\TestResult;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        // Get dashboard statistics
        $totalPatients = Patient::count();
        $totalTests = UrineTest::count();
        $totalResults = TestResult::count();
        
        $recentTests = UrineTest::with('patient')
            ->latest()
            ->take(5)
            ->get();
        
        // Use the correct column name: overall_status
        $normalResults = TestResult::where('overall_status', 'normal')->count();
        $abnormalResults = TestResult::where('overall_status', 'abnormal')->count();
        $needsReviewResults = TestResult::where('overall_status', 'needs_review')->count();
        
        // Get recent results
        $recentResults = TestResult::with(['urineTest.patient'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('dashboard', compact(
            'totalPatients',
            'totalTests', 
            'totalResults',
            'recentTests',
            'recentResults',
            'normalResults',
            'abnormalResults',
            'needsReviewResults'
        ));
    }
}