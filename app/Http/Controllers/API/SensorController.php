<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UrineTest;
use App\Models\TestResult;
use App\Models\AiAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SensorController extends Controller
{
    /**
     * Endpoint untuk menerima data dari sensor mikrokontroler
     * POST /api/v1/sensor/data
     */
    public function receiveData(Request $request)
    {
        // Log incoming request untuk debugging
        Log::info('Sensor data received', [
            'ip' => $request->ip(),
            'data' => $request->all()
        ]);

        // Validasi data dari sensor
        $validator = Validator::make($request->all(), [
            'test_code' => 'required|string|exists:urine_tests,test_code',
            'sensor_id' => 'required|string',
            'timestamp' => 'required|date',
            
            // Data hasil sensor
            'ph_level' => 'required|numeric|between:0,14',
            'protein' => 'required|numeric|min:0',
            'glucose' => 'required|numeric|min:0',
            'color' => 'required|string|max:50',
            'temperature' => 'required|numeric|between:0,50',
            'volume' => 'required|integer|min:0',
            'specific_gravity' => 'required|numeric|between:1.000,1.050',
            
            // Data mentah sensor (opsional)
            'raw_data' => 'nullable|array',
            'calibration_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            Log::warning('Sensor data validation failed', [
                'errors' => $validator->errors(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data sensor tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Cari test yang sedang pending
            $urineTest = UrineTest::where('test_code', $request->test_code)
                                 ->where('status', 'pending')
                                 ->first();

            if (!$urineTest) {
                Log::warning('Test not found or not pending', [
                    'test_code' => $request->test_code
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Test tidak ditemukan atau sudah selesai',
                    'test_code' => $request->test_code
                ], 404);
            }

            // Buat record hasil tes
            $testResult = TestResult::create([
                'urine_test_id' => $urineTest->id,
                'ph_level' => $request->ph_level,
                'protein' => $request->protein,
                'glucose' => $request->glucose,
                'color' => $request->color,
                'temperature' => $request->temperature,
                'volume' => $request->volume,
                'specific_gravity' => $request->specific_gravity,
                'raw_sensor_data' => [
                    'sensor_id' => $request->sensor_id,
                    'timestamp' => $request->timestamp,
                    'raw_data' => $request->raw_data,
                    'calibration_data' => $request->calibration_data,
                    'received_at' => now()->toISOString()
                ],
                'overall_status' => $this->determineOverallStatus($request)
            ]);

            // Update status test menjadi completed
            $urineTest->update([
                'status' => 'completed',
                'test_date' => now()
            ]);

            // Jalankan analisis AI (async)
            $this->runAiAnalysis($testResult);

            Log::info('Sensor data processed successfully', [
                'test_code' => $request->test_code,
                'test_result_id' => $testResult->id,
                'overall_status' => $testResult->overall_status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data sensor berhasil diterima dan diproses',
                'data' => [
                    'test_code' => $urineTest->test_code,
                    'test_result_id' => $testResult->id,
                    'overall_status' => $testResult->overall_status,
                    'processed_at' => now()->toISOString()
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error processing sensor data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses data sensor',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Endpoint untuk sensor melakukan ping/health check
     * POST /api/v1/sensor/ping
     */
    public function ping(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sensor_id' => 'required|string',
            'firmware_version' => 'nullable|string',
            'battery_level' => 'nullable|integer|between:0,100',
            'signal_strength' => 'nullable|integer|between:0,100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data ping tidak valid',
                'errors' => $validator->errors()
            ], 422);
        }

        // Log ping untuk monitoring
        Log::info('Sensor ping received', [
            'sensor_id' => $request->sensor_id,
            'ip' => $request->ip(),
            'firmware_version' => $request->firmware_version,
            'battery_level' => $request->battery_level,
            'signal_strength' => $request->signal_strength,
            'timestamp' => now()
        ]);

        // Simpan/update status sensor di cache atau database
        cache()->put(
            'sensor_status_' . $request->sensor_id,
            [
                'last_ping' => now(),
                'ip_address' => $request->ip(),
                'firmware_version' => $request->firmware_version,
                'battery_level' => $request->battery_level ?? 100,
                'signal_strength' => $request->signal_strength ?? 100,
                'status' => 'online'
            ],
            now()->addMinutes(5) // Expire after 5 minutes
        );

        return response()->json([
            'success' => true,
            'message' => 'Ping diterima',
            'server_time' => now()->toISOString(),
            'instructions' => [
                'keep_alive_interval' => 60, // seconds
                'data_endpoint' => url('/api/v1/sensor/data'),
                'max_retry_attempts' => 3
            ]
        ], 200);
    }

    /**
     * Endpoint untuk mendapatkan daftar test yang pending
     * GET /api/v1/sensor/pending-tests
     */
    public function getPendingTests(Request $request)
    {
        try {
            $pendingTests = UrineTest::where('status', 'pending')
                                   ->where('created_at', '>=', now()->subHours(24)) // Hanya tes 24 jam terakhir
                                   ->with(['patient:id,name,patient_id'])
                                   ->select(['id', 'test_code', 'patient_id', 'created_at'])
                                   ->orderBy('created_at', 'asc')
                                   ->limit(10)
                                   ->get();

            return response()->json([
                'success' => true,
                'data' => $pendingTests->map(function ($test) {
                    return [
                        'test_code' => $test->test_code,
                        'patient_name' => $test->patient->name,
                        'patient_id' => $test->patient->patient_id,
                        'created_at' => $test->created_at->toISOString(),
                        'waiting_time_minutes' => $test->created_at->diffInMinutes(now())
                    ];
                }),
                'count' => $pendingTests->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error getting pending tests', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data test pending'
            ], 500);
        }
    }

    /**
     * Tentukan status overall berdasarkan parameter
     */
    private function determineOverallStatus(Request $request)
    {
        $abnormalCount = 0;
        $reviewCount = 0;

        // Cek pH level (normal: 4.6-8.0)
        if ($request->ph_level < 4.6 || $request->ph_level > 8.0) {
            $abnormalCount++;
        }

        // Cek protein (normal: ≤ 150 mg/dL)
        if ($request->protein > 150) {
            if ($request->protein > 300) {
                $abnormalCount++;
            } else {
                $reviewCount++;
            }
        }

        // Cek glucose (normal: ≤ 15 mg/dL)
        if ($request->glucose > 15) {
            if ($request->glucose > 50) {
                $abnormalCount++;
            } else {
                $reviewCount++;
            }
        }

        // Cek suhu (normal: 36-38°C)
        if ($request->temperature < 36 || $request->temperature > 38) {
            $reviewCount++;
        }

        // Cek berat jenis (normal: 1.003-1.030)
        if ($request->specific_gravity < 1.003 || $request->specific_gravity > 1.030) {
            if ($request->specific_gravity < 1.001 || $request->specific_gravity > 1.035) {
                $abnormalCount++;
            } else {
                $reviewCount++;
            }
        }

        // Tentukan status berdasarkan jumlah parameter abnormal
        if ($abnormalCount >= 2) {
            return 'abnormal';
        } elseif ($abnormalCount >= 1 || $reviewCount >= 2) {
            return 'needs_review';
        } else {
            return 'normal';
        }
    }

    /**
     * Jalankan analisis AI (simulasi - nanti diganti dengan real AI)
     */
    private function runAiAnalysis(TestResult $testResult)
    {
        try {
            // Simulasi delay AI processing
            // sleep(2);

            $analysis = $this->generateAiAnalysis($testResult);

            AiAnalysis::create([
                'test_result_id' => $testResult->id,
                'summary' => $analysis['summary'],
                'detailed_analysis' => $analysis['detailed_analysis'],
                'recommendations' => $analysis['recommendations'],
                'confidence_score' => $analysis['confidence_score'],
                'risk_level' => $analysis['risk_level'],
                'potential_conditions' => $analysis['potential_conditions']
            ]);

            Log::info('AI analysis completed', [
                'test_result_id' => $testResult->id,
                'confidence_score' => $analysis['confidence_score'],
                'risk_level' => $analysis['risk_level']
            ]);

        } catch (\Exception $e) {
            Log::error('Error in AI analysis', [
                'test_result_id' => $testResult->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Generate AI analysis (simulasi)
     */
    private function generateAiAnalysis(TestResult $testResult)
    {
        $abnormalFindings = [];
        $recommendations = [];
        $potentialConditions = [];
        $riskLevel = 'low';

        // Analisis pH Level
        if ($testResult->ph_level < 4.6) {
            $abnormalFindings[] = "pH urin sangat asam ({$testResult->ph_level})";
            $recommendations[] = "Evaluasi kemungkinan infeksi saluran kemih atau gangguan metabolisme";
            $potentialConditions[] = "Acidosis metabolik";
            $potentialConditions[] = "Infeksi saluran kemih";
            $riskLevel = 'medium';
        } elseif ($testResult->ph_level > 8.0) {
            $abnormalFindings[] = "pH urin sangat basa ({$testResult->ph_level})";
            $recommendations[] = "Periksa kemungkinan infeksi bakteri atau gangguan ginjal";
            $potentialConditions[] = "Infeksi bakteri Proteus";
            $potentialConditions[] = "Alkalosis respiratorik";
            $riskLevel = 'medium';
        }

        // Analisis Protein
        if ($testResult->protein > 300) {
            $abnormalFindings[] = "Proteinuria berat ({$testResult->protein} mg/dL)";
            $recommendations[] = "Rujuk ke nephrologist untuk evaluasi lebih lanjut";
            $potentialConditions[] = "Penyakit ginjal kronis";
            $potentialConditions[] = "Glomerulonefritis";
            $riskLevel = 'high';
        } elseif ($testResult->protein > 150) {
            $abnormalFindings[] = "Proteinuria ringan hingga sedang ({$testResult->protein} mg/dL)";
            $recommendations[] = "Monitor fungsi ginjal dan ulangi tes dalam 1-2 minggu";
            $potentialConditions[] = "Proteinuria orthostatik";
            $potentialConditions[] = "Penyakit ginjal dini";
            if ($riskLevel === 'low') $riskLevel = 'medium';
        }

        // Analisis Glukosa
        if ($testResult->glucose > 50) {
            $abnormalFindings[] = "Glukosuria signifikan ({$testResult->glucose} mg/dL)";
            $recommendations[] = "Evaluasi diabetes mellitus dan kontrol gula darah";
            $potentialConditions[] = "Diabetes mellitus";
            $potentialConditions[] = "Gangguan toleransi glukosa";
            $riskLevel = 'high';
        } elseif ($testResult->glucose > 15) {
            $abnormalFindings[] = "Glukosa urin meningkat ({$testResult->glucose} mg/dL)";
            $recommendations[] = "Periksa kadar gula darah puasa dan HbA1c";
            $potentialConditions[] = "Pre-diabetes";
            $potentialConditions[] = "Stress hyperglycemia";
            if ($riskLevel === 'low') $riskLevel = 'medium';
        }

        // Analisis Berat Jenis
        if ($testResult->specific_gravity < 1.003) {
            $abnormalFindings[] = "Berat jenis urin rendah ({$testResult->specific_gravity})";
            $recommendations[] = "Evaluasi kemampuan konsentrasi ginjal";
            $potentialConditions[] = "Diabetes insipidus";
            $potentialConditions[] = "Gangguan fungsi tubulus ginjal";
        } elseif ($testResult->specific_gravity > 1.030) {
            $abnormalFindings[] = "Berat jenis urin tinggi ({$testResult->specific_gravity})";
            $recommendations[] = "Periksa status hidrasi dan fungsi ginjal";
            $potentialConditions[] = "Dehidrasi";
            $potentialConditions[] = "Diabetes mellitus";
        }

        // Analisis Warna
        $normalColors = ['yellow', 'pale yellow', 'light yellow', 'kuning muda', 'kuning'];
        if (!in_array(strtolower($testResult->color), $normalColors)) {
            $abnormalFindings[] = "Warna urin abnormal ({$testResult->color})";
            
            if (in_array(strtolower($testResult->color), ['red', 'merah', 'pink', 'merah muda'])) {
                $recommendations[] = "Evaluasi kemungkinan hematuria atau infeksi";
                $potentialConditions[] = "Hematuria";
                $potentialConditions[] = "Infeksi saluran kemih";
                $riskLevel = 'high';
            } elseif (in_array(strtolower($testResult->color), ['dark', 'coklat', 'brown'])) {
                $recommendations[] = "Periksa fungsi hati dan kemungkinan hemolisis";
                $potentialConditions[] = "Gangguan hati";
                $potentialConditions[] = "Hemolisis";
                if ($riskLevel !== 'high') $riskLevel = 'medium';
            }
        }

        // Generate summary
        if (empty($abnormalFindings)) {
            $summary = "Hasil analisis urin menunjukkan parameter dalam batas normal. Tidak ditemukan kelainan yang signifikan.";
            $confidenceScore = 95.5;
        } else {
            $summary = "Ditemukan " . count($abnormalFindings) . " parameter abnormal: " . implode(', ', array_slice($abnormalFindings, 0, 3));
            if (count($abnormalFindings) > 3) {
                $summary .= " dan lainnya.";
            }
            $confidenceScore = max(70, 95 - (count($abnormalFindings) * 5));
        }

        // Default recommendations jika tidak ada yang spesifik
        if (empty($recommendations)) {
            $recommendations[] = "Pertahankan pola hidup sehat dan konsumsi air yang cukup";
            $recommendations[] = "Lakukan pemeriksaan rutin sesuai jadwal yang direkomendasikan";
        }

        // Generate detailed analysis
        $detailedAnalysis = [
            'ph_analysis' => [
                'value' => $testResult->ph_level,
                'normal_range' => '4.6 - 8.0',
                'status' => $testResult->isPhNormal() ? 'normal' : 'abnormal',
                'interpretation' => $testResult->isPhNormal() ? 
                    'pH urin dalam batas normal' : 
                    'pH urin di luar batas normal, perlu evaluasi lebih lanjut'
            ],
            'protein_analysis' => [
                'value' => $testResult->protein,
                'normal_range' => '≤ 150 mg/dL',
                'status' => $testResult->isProteinNormal() ? 'normal' : 'abnormal',
                'interpretation' => $testResult->isProteinNormal() ? 
                    'Kadar protein normal' : 
                    'Proteinuria terdeteksi, diperlukan evaluasi fungsi ginjal'
            ],
            'glucose_analysis' => [
                'value' => $testResult->glucose,
                'normal_range' => '≤ 15 mg/dL', 
                'status' => $testResult->isGlucoseNormal() ? 'normal' : 'abnormal',
                'interpretation' => $testResult->isGlucoseNormal() ? 
                    'Kadar glukosa normal' : 
                    'Glukosuria terdeteksi, periksa kadar gula darah'
            ],
            'physical_analysis' => [
                'color' => [
                    'value' => $testResult->color,
                    'normal_range' => 'Kuning muda hingga kuning',
                    'status' => in_array(strtolower($testResult->color), $normalColors) ? 'normal' : 'abnormal'
                ],
                'specific_gravity' => [
                    'value' => $testResult->specific_gravity,
                    'normal_range' => '1.003 - 1.030',
                    'status' => ($testResult->specific_gravity >= 1.003 && $testResult->specific_gravity <= 1.030) ? 'normal' : 'abnormal'
                ]
            ]
        ];

        return [
            'summary' => $summary,
            'detailed_analysis' => $detailedAnalysis,
            'recommendations' => implode('. ', $recommendations) . '.',
            'confidence_score' => $confidenceScore,
            'risk_level' => $riskLevel,
            'potential_conditions' => array_unique($potentialConditions)
        ];
    }
}