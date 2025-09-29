<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\UrineTestController;


// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (harus login)
Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard Routes
    Route::get('/dashboard', function () {
        return view('layouts.dashboard');
    })->name('dashboard');

    // Test Routes
    Route::get('/test-baru', function () {
        return view('layouts.test-baru');
    })->name('test-baru');

    
    Route::get('/hasil-tes', [UrineTestController::class, 'index'])->name('hasil-tes');

    // AI Analysis Routes
    Route::get('/analisis-ai', function () {
        return view('layouts.analisis-ai'); 
    })->name('analisis-ai');

    // Patient Data Routes
    Route::get('/data-pasien', [PatientController::class, 'index'])->name('data-pasien');
    
    //  routes untuk PatientController
    Route::post('/data-pasien', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/data-pasien/{id}', [PatientController::class, 'show'])->name('patients.show');
    Route::put('/data-pasien/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::delete('/data-pasien/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::get('/data-pasien/export', [PatientController::class, 'export'])->name('patients.export');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

    // API Routes for AJAX requests
    Route::prefix('api')->group(function () {
        Route::post('/patients', [PatientController::class, 'store']);
        Route::put('/patients/{id}', [PatientController::class, 'update']);
        Route::delete('/patients/{id}', [PatientController::class, 'destroy']);
        Route::get('/patients/{id}', [PatientController::class, 'show']);
        
        Route::post('/tests', function () {
            return response()->json(['status' => 'success', 'message' => 'Tes baru berhasil dibuat']);
        });
        
        Route::get('/tests/{id}', function ($id) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'test_id' => $id,
                    'patient_name' => 'Sample Patient',
                    'results' => []
                ]
            ]);
        });
    });
});