<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Analysis;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        
        // Redirect admin users to admin dashboard
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        // Get user's analyses
        $analyses = $user->analyses()->latest()->take(5)->get();
        $totalAnalyses = $user->analyses()->count();
        
        // Calculate average BMI
        $avgBMI = $user->analyses()->avg('bmi');
        
        // Get latest analysis
        $latestAnalysis = $user->analyses()->latest()->first();
        
        return view('dashboard', compact(
            'user',
            'analyses',
            'totalAnalyses',
            'avgBMI',
            'latestAnalysis'
        ));
    }
}
