<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PatientController extends Controller
{
    // Tampilkan halaman data pasien
    public function index()
    {
        $patients = Patient::orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total' => Patient::count(),
            'active' => Patient::where('status', 'active')->count(),
            'newThisMonth' => Patient::whereMonth('register_date', Carbon::now()->month)
                                     ->whereYear('register_date', Carbon::now()->year)
                                     ->count(),
            'totalTests' => Patient::sum('total_tests'),
        ];
        
        return view('layouts.data-pasien', compact('patients', 'stats'));
    }

    // Simpan pasien baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $patient = new Patient($validated);
        $patient->patient_id = Patient::generatePatientId();
        $patient->age = Carbon::parse($validated['birthdate'])->age;
        $patient->register_date = Carbon::now();
        $patient->status = 'active';
        $patient->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pasien berhasil ditambahkan',
                'data' => $patient
            ]);
        }

        return redirect()->route('data-pasien')->with('success', 'Pasien berhasil ditambahkan');
    }

    // Detail pasien
    public function show($id)
    {
        $patient = Patient::with('urineTests')->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $patient
        ]);
    }

    // Update data pasien
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:L,P',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $patient->update($validated);
        $patient->age = Carbon::parse($validated['birthdate'])->age;
        $patient->save();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data pasien berhasil diperbarui',
                'data' => $patient
            ]);
        }

        return redirect()->route('data-pasien')->with('success', 'Data pasien berhasil diperbarui');
    }

    // Hapus pasien (soft delete)
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Pasien berhasil dihapus'
        ]);
    }
    public static function generatePatientId()
    {
        $lastPatient = self::withTrashed()->orderBy('id', 'desc')->first();
        $lastNumber = $lastPatient ? intval(substr($lastPatient->patient_id, 3)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return 'PAT' . $newNumber; // Format: PAT0001, PAT0002, dst
    }
   
    public function export()
    {
        $patients = Patient::all();
        
        // TODO: Implement Excel export using Laravel Excel
        return response()->json([
            'status' => 'success',
            'message' => 'Fitur export akan segera tersedia'
        ]);
    }
}