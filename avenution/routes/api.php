<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnalysisController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Analysis & Recommendation API Routes
|--------------------------------------------------------------------------
*/

// Public routes (no authentication required - for guest users)
Route::prefix('analysis')->group(function () {
    // Create new analysis and get recommendations
    Route::post('/', [AnalysisController::class, 'store']);
    
    // Get specific analysis by ID
    Route::get('/{id}', [AnalysisController::class, 'show']);
    
    // Get user's analysis history
    Route::get('/history/user', [AnalysisController::class, 'history']);
});

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Future: User-specific routes
    // Route::get('/my-analyses', [AnalysisController::class, 'myAnalyses']);
    // Route::delete('/analysis/{id}', [AnalysisController::class, 'destroy']);
});
