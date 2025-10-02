<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestResult;
use Illuminate\Support\Facades\Log;

class AnalysisController extends Controller
{
    public function store(Request $request)
    {
        try {
            // log request masuk
            Log::info('Incoming request:', $request->all());

            $validated = $request->validate([
                'urine_test_id'    => 'required|integer|exists:urine_tests,id',
                'ph_level'         => 'required|numeric',
                'protein'          => 'required|numeric',
                'glucose'          => 'required|numeric',
                'color'            => 'required|string',
                'temperature'      => 'required|numeric',
                'volume'           => 'required|integer',
                'specific_gravity' => 'required|numeric',
                'raw_sensor_data'  => 'nullable|array',
                'overall_status'   => 'required|in:normal,abnormal,needs_review',
            ]);

            $result = TestResult::create($validated);

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error('Insert failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save test result: ' . $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        // carikan test result terbaru
        $result = TestResult::latest()->first();
        // dd($last_result); // dump and die
        $last_result = [
            'ph_level' => $result->ph_level,
            'color' => $result->color,
            'mass' => ($result->raw_sensor_data['mass'] ?? null) . " grams",
            'velocity' => ($result->raw_sensor_data['velocity'] ?? null) . " g/ml",

        ];
        $analisis_ai = $result->raw_sensor_data['analisis_ai'] ?? null;
        return view('layouts.analisis-ai', compact(
            'last_result',
            'analisis_ai'
        ));
    }

}