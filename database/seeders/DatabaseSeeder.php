<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Patient;
use App\Models\UrineTest;
use App\Models\TestResult;
use Carbon\Carbon;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // admiN user
        $user = User::create([
            'name' => 'Dr. Admin UroMed',
            'email' => 'admin@uromed.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        //operator
        User::create([
            'name' => 'Operator UroMed',
            'email' => 'operator@uromed.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        // Dummy Patient
        $patient = Patient::firstOrCreate(
            ['patient_id' => 'P-001'],
            [
                'name' => 'Dummy Patient',
                'birthdate' => '1990-01-01',
                'age' => 34,
                'gender' => 'male',
                'phone' => '08123456789',
                'email' => 'dummy.patient@example.com',
                'address' => 'Jl. Testing No.1',
                'medical_history' => 'None',
                'register_date' => Carbon::now(),
                'status' => 'active',
                'total_tests' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );

        // Dummy UrineTest
        UrineTest::firstOrCreate(
            ['test_id' => 'TST-001'],
            [
                'test_code'  => 'UM250930',
                'patient_id' => $patient->id,
                'user_id'    => $user->id,
                'test_date'  => Carbon::now(),
                'status'     => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        );
        $data = [
            "analysis" => "Berdasarkan parameter...",
            "solve_step" => "3. Perhatikan pola makan dan hidrasi...",
            "risk_disease" => [
                [
                    "name" => "Diabetes",
                    "percentage" => 85,
                    "description" => "Tingkat gula darah tinggi...",
                    "based_on" => "Kadar glukosa dalam urin",
                ]
                ],
            'overall_status' => 'normal',
        ];
        TestResult::firstOrCreate(
            // parameter untuk pencarian
            ['urine_test_id' => 1],

            // value yang diisi kalau tidak ada
            [
                'ph_level' => 3.00,
                'protein' => 0.00,
                'glucose' => 0.00,
                'color' => 'red',
                'temperature' => 0.00,
                'volume' => 250,
                'specific_gravity' => 0.000,
                'raw_sensor_data' => [
                    "mass" => "clear",
                    "velocity" => 1000,
                    "analisis_ai" => $data,
                ],
                'overall_status' => 'normal',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );


        echo "✅ Database seeding completed!\n";
        echo "📧 Admin Email: admin@uromed.com\n";
        echo "🔑 Password: password123\n\n";
        echo "📧 Operator Email: operator@uromed.com\n";
        echo "🔑 Password: password123\n\n";
    }
}
