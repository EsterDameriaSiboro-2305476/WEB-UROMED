<?php

namespace App\Http\Controllers;

use App\Models\UrineTest;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UrineTestController extends Controller
{
    /**
     * Display a listing of urine tests
     */
    public function index(Request $request)
    {
        $query = UrineTest::with('patient')->orderBy('test_date', 'desc');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('test_id', 'like', "%{$search}%")
                  ->orWhereHas('patient', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('patient_id', 'like', "%{$search}%");
                  });
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            $query->where('result_status', $request->status);
        }
        
        // Date filter
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'today':
                    $query->whereDate('test_date', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('test_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('test_date', Carbon::now()->month)
                          ->whereYear('test_date', Carbon::now()->year);
                    break;
            }
        }
        
        $urineTests = $query->paginate(10);
        
        // Statistics
        $stats = [
            'total' => UrineTest::count(),
            'normal' => UrineTest::where('result_status', 'Normal')->count(),
            'perhatian' => UrineTest::where('result_status', 'Perhatian')->count(),
            'abnormal' => UrineTest::where('result_status', 'Abnormal')->count(),
        ];
        
        return view('layouts.hasil-tes', compact('urineTests', 'stats'));
    }

    /**
     * Show the form for creating a new test
     */
    public function create()
    {
        $patients = Patient::where('status', 'active')
                          ->orderBy('name')
                          ->get();
        
        return view('layouts.test-baru', compact('patients'));
    }

    /**
     * Store a newly created test
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'test_date' => 'required|date',
            'ph_level' => 'nullable|numeric|min:0|max:14',
            'protein' => 'nullable|string',
            'glucose' => 'nullable|string',
            'ketones' => 'nullable|string',
            'blood' => 'nullable|string',
            'color' => 'nullable|string',
            'clarity' => 'nullable|string',
            'specific_gravity' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Generate test ID
            $testId = $this->generateTestId();
            
            // Determine status based on results
            $resultStatus = $this->determineStatus($validated);
            
            // Create test
            $urineTest = new UrineTest();
            $urineTest->test_id = $testId;
            $urineTest->patient_id = $validated['patient_id'];
            $urineTest->test_date = $validated['test_date'];
            $urineTest->ph_level = $validated['ph_level'] ?? null;
            $urineTest->protein = $validated['protein'] ?? null;
            $urineTest->glucose = $validated['glucose'] ?? null;
            $urineTest->ketones = $validated['ketones'] ?? null;
            $urineTest->blood = $validated['blood'] ?? null;
            $urineTest->color = $validated['color'] ?? null;
            $urineTest->clarity = $validated['clarity'] ?? null;
            $urineTest->specific_gravity = $validated['specific_gravity'] ?? null;
            $urineTest->result_status = $resultStatus;
            $urineTest->notes = $validated['notes'] ?? null;
            
            // Set individual parameter statuses
            $urineTest->ph_status = $this->checkPhStatus($validated['ph_level'] ?? null);
            $urineTest->protein_status = $this->checkProteinStatus($validated['protein'] ?? null);
            $urineTest->glucose_status = $this->checkGlucoseStatus($validated['glucose'] ?? null);
            $urineTest->ketones_status = $this->checkKetonesStatus($validated['ketones'] ?? null);
            $urineTest->blood_status = $this->checkBloodStatus($validated['blood'] ?? null);
            $urineTest->color_status = $this->checkColorStatus($validated['color'] ?? null);
            $urineTest->clarity_status = $this->checkClarityStatus($validated['clarity'] ?? null);
            $urineTest->gravity_status = $this->checkGravityStatus($validated['specific_gravity'] ?? null);
            
            $urineTest->save();
            
            // Update patient total tests
            $patient = Patient::find($validated['patient_id']);
            $patient->increment('total_tests');
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tes berhasil disimpan',
                    'data' => $urineTest
                ]);
            }
            
            return redirect()->route('hasil-tes')
                           ->with('success', 'Tes berhasil disimpan');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan tes: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Gagal menyimpan tes'])
                        ->withInput();
        }
    }

    /**
     * Display the specified test (API)
     */
    public function show($id)
    {
        $test = UrineTest::with('patient')->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $test
        ]);
    }

    /**
     * Update the specified test
     */
    public function update(Request $request, $id)
    {
        $test = UrineTest::findOrFail($id);
        
        $validated = $request->validate([
            'ph_level' => 'nullable|numeric|min:0|max:14',
            'protein' => 'nullable|string',
            'glucose' => 'nullable|string',
            'ketones' => 'nullable|string',
            'blood' => 'nullable|string',
            'color' => 'nullable|string',
            'clarity' => 'nullable|string',
            'specific_gravity' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);
        
        // Update test data
        $test->update($validated);
        
        // Recalculate status
        $test->result_status = $this->determineStatus($validated);
        $test->ph_status = $this->checkPhStatus($validated['ph_level'] ?? null);
        $test->protein_status = $this->checkProteinStatus($validated['protein'] ?? null);
        $test->glucose_status = $this->checkGlucoseStatus($validated['glucose'] ?? null);
        $test->ketones_status = $this->checkKetonesStatus($validated['ketones'] ?? null);
        $test->blood_status = $this->checkBloodStatus($validated['blood'] ?? null);
        $test->color_status = $this->checkColorStatus($validated['color'] ?? null);
        $test->clarity_status = $this->checkClarityStatus($validated['clarity'] ?? null);
        $test->gravity_status = $this->checkGravityStatus($validated['specific_gravity'] ?? null);
        $test->save();
        
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tes berhasil diperbarui',
                'data' => $test
            ]);
        }
        
        return redirect()->route('hasil-tes')
                       ->with('success', 'Tes berhasil diperbarui');
    }

    /**
     * Remove the specified test
     */
    public function destroy($id)
    {
        try {
            $test = UrineTest::findOrFail($id);
            
            // Decrement patient total tests
            $patient = $test->patient;
            if ($patient->total_tests > 0) {
                $patient->decrement('total_tests');
            }
            
            $test->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Tes berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus tes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate unique test ID
     */
    private function generateTestId()
    {
        $lastTest = UrineTest::orderBy('id', 'desc')->first();
        $lastNumber = $lastTest ? intval(substr($lastTest->test_id, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return 'UR-' . date('Y') . '-' . $newNumber;
    }

    /**
     * Determine overall test status based on parameters
     */
    private function determineStatus($data)
    {
        $abnormalCount = 0;
        $warningCount = 0;
        
        // Check pH
        if (isset($data['ph_level'])) {
            $ph = floatval($data['ph_level']);
            if ($ph < 4.5 || $ph > 8.0) {
                $abnormalCount++;
            } elseif ($ph < 5.0 || $ph > 7.5) {
                $warningCount++;
            }
        }
        
        // Check protein
        if (isset($data['protein']) && strtolower($data['protein']) !== 'negatif') {
            if (stripos($data['protein'], 'trace') !== false) {
                $warningCount++;
            } else {
                $abnormalCount++;
            }
        }
        
        // Check glucose
        if (isset($data['glucose']) && strtolower($data['glucose']) !== 'negatif') {
            $abnormalCount++;
        }
        
        // Check ketones
        if (isset($data['ketones']) && strtolower($data['ketones']) !== 'negatif') {
            $abnormalCount++;
        }
        
        // Check blood
        if (isset($data['blood']) && strtolower($data['blood']) !== 'negatif') {
            $abnormalCount++;
        }
        
        // Determine final status
        if ($abnormalCount >= 2) {
            return 'Abnormal';
        } elseif ($abnormalCount >= 1 || $warningCount >= 2) {
            return 'Perhatian';
        }
        
        return 'Normal';
    }

    /**
     * Individual parameter status checks
     */
    private function checkPhStatus($ph)
    {
        if (!$ph) return 'Normal';
        $ph = floatval($ph);
        if ($ph < 4.5 || $ph > 8.0) return 'Abnormal';
        if ($ph < 5.0 || $ph > 7.5) return 'Perhatian';
        return 'Normal';
    }

    private function checkProteinStatus($protein)
    {
        if (!$protein) return 'Normal';
        $protein = strtolower($protein);
        if ($protein === 'negatif') return 'Normal';
        if (stripos($protein, 'trace') !== false) return 'Perhatian';
        return 'Abnormal';
    }

    private function checkGlucoseStatus($glucose)
    {
        if (!$glucose) return 'Normal';
        return strtolower($glucose) === 'negatif' ? 'Normal' : 'Abnormal';
    }

    private function checkKetonesStatus($ketones)
    {
        if (!$ketones) return 'Normal';
        return strtolower($ketones) === 'negatif' ? 'Normal' : 'Abnormal';
    }

    private function checkBloodStatus($blood)
    {
        if (!$blood) return 'Normal';
        return strtolower($blood) === 'negatif' ? 'Normal' : 'Abnormal';
    }

    private function checkColorStatus($color)
    {
        if (!$color) return 'Normal';
        $color = strtolower($color);
        $normalColors = ['kuning', 'kuning pucat', 'kuning muda'];
        foreach ($normalColors as $normalColor) {
            if (stripos($color, $normalColor) !== false) {
                return 'Normal';
            }
        }
        return 'Perhatian';
    }

    private function checkClarityStatus($clarity)
    {
        if (!$clarity) return 'Normal';
        return strtolower($clarity) === 'jernih' ? 'Normal' : 'Perhatian';
    }

    private function checkGravityStatus($gravity)
    {
        if (!$gravity) return 'Normal';
        $gravity = floatval($gravity);
        if ($gravity < 1.003 || $gravity > 1.035) return 'Abnormal';
        if ($gravity < 1.005 || $gravity > 1.030) return 'Perhatian';
        return 'Normal';
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        // TODO: Implement Excel export using Laravel Excel package
        return response()->json([
            'status' => 'info',
            'message' => 'Fitur export Excel akan segera tersedia'
        ]);
    }

    /**
     * Export to PDF
     */
    public function exportPDF($id)
    {
        // TODO: Implement PDF export using DomPDF or similar
        $test = UrineTest::with('patient')->findOrFail($id);
        
        return response()->json([
            'status' => 'info',
            'message' => 'Fitur export PDF akan segera tersedia'
        ]);
    }

    /**
     * Print report
     */
    public function print($id)
    {
        $test = UrineTest::with('patient')->findOrFail($id);
        
        return view('layouts.print-test', compact('test'));
    }
}