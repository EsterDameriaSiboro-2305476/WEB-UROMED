<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\UrineTest;
use App\Models\TestResult;
use App\Models\AiAnalysis;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Dr. Admin UroMed',
            'email' => 'admin@uromed.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        // Create sample operator
        User::create([
            'name' => 'Operator UroMed',
            'email' => 'operator@uromed.com', 
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        // Create sample patients
        $patients = [
            [
                'patient_id' => 'PAT00001',
                'name' => 'Budi Santoso',
                'age' => 45,
                'gender' => 'male',
                'phone' => '081234567890',
                'medical_history' => 'Riwayat hipertensi, tidak ada riwayat diabetes'
            ],
            [
                'patient_id' => 'PAT00002',
                'name' => 'Siti Aminah',
                'age' => 38,
                'gender' => 'female',
                'phone' => '081234567891',
                'medical_history' => 'Sehat, tidak ada riwayat penyakit kronis'
            ],
            [
                'patient_id' => 'PAT00003', 
                'name' => 'Ahmad Wijaya',
                'age' => 52,
                'gender' => 'male',
                'phone' => '081234567892',
                'medical_history' => 'Diabetes mellitus tipe 2, kontrol rutin'
            ],
            [
                'patient_id' => 'PAT00004',
                'name' => 'Dewi Kartika',
                'age' => 29,
                'gender' => 'female', 
                'phone' => '081234567893',
                'medical_history' => 'Hamil trimester 2, kontrol rutin kehamilan'
            ],
            [
                'patient_id' => 'PAT00005',
                'name' => 'Hendra Gunawan',
                'age' => 61,
                'gender' => 'male',
                'phone' => '081234567894',
                'medical_history' => 'Riwayat batu ginjal, hipertensi, kolesterol tinggi'
            ]
        ];

        foreach ($patients as $patientData) {
            $patient = Patient::create($patientData);
            
            // Create sample tests for each patient
            $this->createSampleTests($patient);
        }

        echo "✅ Database seeding completed!\n";
        echo "📧 Admin Email: admin@uromed.com\n"; 
        echo "🔑 Password: password123\n\n";
        echo "📧 Operator Email: operator@uromed.com\n";
        echo "🔑 Password: password123\n\n";
    }

    private function createSampleTests(Patient $patient)
    {
        // Create 2-5 sample tests per patient with varying dates
        $testCount = rand(2, 5);
        
        for ($i = 0; $i < $testCount; $i++) {
            $testDate = Carbon::now()->subDays(rand(1, 30));
            
            $test = UrineTest::create([
                'test_code' => $this->generateTestCode($testDate),
                'patient_id' => $patient->id,
                'user_id' => 1, // Admin user
                'status' => 'completed',
                'test_date' => $testDate,
                'notes' => $this->generateTestNotes($patient)
            ]);

            // Create test result
            $resultData = $this->generateSampleResult($patient);
            
            $testResult = TestResult::create([
                'urine_test_id' => $test->id,
                'ph_level' => $resultData['ph_level'],
                'protein' => $resultData['protein'],
                'glucose' => $resultData['glucose'],
                'color' => $resultData['color'],
                'temperature' => $resultData['temperature'],
                'volume' => $resultData['volume'],
                'specific_gravity' => $resultData['specific_gravity'],
                'raw_sensor_data' => [
                    'sensor_id' => 'UROMED_DEMO_001',
                    'timestamp' => $testDate->toISOString(),
                    'calibration_status' => 'ok',
                    'measurement_duration' => rand(30, 90)
                ],
                'overall_status' => $resultData['overall_status']
            ]);

            // Create AI analysis
            $this->createSampleAiAnalysis($testResult);
        }

        // Create one pending test
        UrineTest::create([
            'test_code' => $this->generateTestCode(now()),
            'patient_id' => $patient->id,
            'user_id' => 1,
            'status' => 'pending',
            'test_date' => now(),
            'notes' => 'Tes pending untuk demo sensor'
        ]);
    }

    private function generateTestCode($date)
    {
        $prefix = 'UM';
        $dateStr = $date->format('ymd');
        $sequence = rand(1, 999);
        
        return $prefix . $dateStr . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    private function generateTestNotes($patient)
    {
        $notes = [
            'Tes rutin kesehatan',
            'Kontrol berkala', 
            'Keluhan BAK keruh',
            'Pemeriksaan pre-operatif',
            'Follow up pengobatan',
            'Tes atas permintaan dokter',
            'Kontrol diabetes',
            'Pemeriksaan kesehatan umum'
        ];

        return $notes[array_rand($notes)];
    }

    private function generateSampleResult($patient)
    {
        // Generate realistic test results based on patient condition
        $baseData = [
            'ph_level' => round(rand(45, 80) / 10, 1), // 4.5 - 8.0
            'protein' => rand(10, 200), // 10-200 mg/dL
            'glucose' => rand(0, 100), // 0-100 mg/dL
            'temperature' => round(rand(350, 390) / 10, 1), // 35.0 - 39.0°C
            'volume' => rand(50, 300), // 50-300 mL
            'specific_gravity' => round(rand(1000, 1035) / 1000, 3), // 1.000 - 1.035
            'color' => ['kuning muda', 'kuning', 'kuning pekat', 'jernih'][array_rand(['kuning muda', 'kuning', 'kuning pekat', 'jernih'])]
        ];

        // Adjust based on medical history
        if (strpos(strtolower($patient->medical_history), 'diabetes') !== false) {
            $baseData['glucose'] = rand(20, 150); // Higher glucose for diabetic patients
            if (rand(1, 3) == 1) { // 33% chance of proteinuria in diabetics
                $baseData['protein'] = rand(200, 500);
            }
        }

        if (strpos(strtolower($patient->medical_history), 'ginjal') !== false || 
            strpos(strtolower($patient->medical_history), 'batu') !== false) {
            $baseData['protein'] = rand(150, 400); // Higher protein
            $baseData['ph_level'] = rand(40, 55) / 10; // More acidic
        }

        if (strpos(strtolower($patient->medical_history), 'hamil') !== false) {
            $baseData['protein'] = rand(100, 250); // Slight proteinuria common in pregnancy
            $baseData['glucose'] = rand(10, 50); // Mild glucosuria possible
        }

        // Determine overall status
        $abnormalCount = 0;
        
        if ($baseData['ph_level'] < 4.6 || $baseData['ph_level'] > 8.0) $abnormalCount++;
        if ($baseData['protein'] > 150) $abnormalCount++;
        if ($baseData['glucose'] > 15) $abnormalCount++;
        if ($baseData['specific_gravity'] < 1.003 || $baseData['specific_gravity'] > 1.030) $abnormalCount++;

        if ($abnormalCount >= 2) {
            $baseData['overall_status'] = 'abnormal';
        } elseif ($abnormalCount >= 1) {
            $baseData['overall_status'] = rand(1, 2) == 1 ? 'needs_review' : 'abnormal';
        } else {
            $baseData['overall_status'] = 'normal';
        }

        return $baseData;
    }

    private function createSampleAiAnalysis($testResult)
    {
        $analysisTemplates = [
            'normal' => [
                'summary' => 'Hasil analisis urin menunjukkan semua parameter dalam batas normal. Tidak ditemukan kelainan yang memerlukan perhatian khusus.',
                'risk_level' => 'low',
                'confidence_score' => rand(90, 98),
                'recommendations' => 'Pertahankan pola hidup sehat, konsumsi air yang cukup, dan lakukan pemeriksaan kesehatan rutin sesuai jadwal.',
                'conditions' => []
            ],
            'needs_review' => [
                'summary' => 'Ditemukan beberapa parameter yang sedikit di luar batas normal namun masih dalam rentang yang dapat ditoleransi. Perlu pemantauan lebih lanjut.',
                'risk_level' => 'medium',
                'confidence_score' => rand(75, 88),
                'recommendations' => 'Ulangi pemeriksaan dalam 2-4 minggu. Jaga asupan cairan dan hindari makanan tinggi garam. Konsultasi dengan dokter jika ada keluhan.',
                'conditions' => ['Dehidrasi ringan', 'Perubahan pola makan']
            ],
            'abnormal' => [
                'summary' => 'Terdeteksi beberapa parameter abnormal yang memerlukan evaluasi medis lebih lanjut. Disarankan untuk berkonsultasi dengan dokter segera.',
                'risk_level' => 'high', 
                'confidence_score' => rand(70, 85),
                'recommendations' => 'Segera konsultasi dengan dokter untuk evaluasi lebih lanjut. Kemungkinan diperlukan pemeriksaan tambahan seperti tes darah atau imaging.',
                'conditions' => ['Infeksi saluran kemih', 'Gangguan fungsi ginjal', 'Diabetes mellitus']
            ]
        ];

        $template = $analysisTemplates[$testResult->overall_status];
        
        AiAnalysis::create([
            'test_result_id' => $testResult->id,
            'summary' => $template['summary'],
            'detailed_analysis' => [
                'ph_analysis' => [
                    'value' => $testResult->ph_level,
                    'status' => $testResult->isPhNormal() ? 'normal' : 'abnormal',
                    'interpretation' => $testResult->isPhNormal() ? 
                        'pH dalam batas normal' : 
                        'pH di luar batas normal, perlu evaluasi'
                ],
                'protein_analysis' => [
                    'value' => $testResult->protein,
                    'status' => $testResult->isProteinNormal() ? 'normal' : 'abnormal',
                    'interpretation' => $testResult->isProteinNormal() ? 
                        'Kadar protein normal' : 
                        'Proteinuria terdeteksi'
                ],
                'glucose_analysis' => [
                    'value' => $testResult->glucose,
                    'status' => $testResult->isGlucoseNormal() ? 'normal' : 'abnormal',
                    'interpretation' => $testResult->isGlucoseNormal() ? 
                        'Kadar glukosa normal' : 
                        'Glukosuria terdeteksi'
                ]
            ],
            'recommendations' => $template['recommendations'],
            'confidence_score' => $template['confidence_score'],
            'risk_level' => $template['risk_level'],
            'potential_conditions' => $template['conditions']
        ]);
    }
}
