<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'name',
        'birthdate',
        'age',
        'gender',
        'phone',
        'email',
        'address',
        'medical_history',
        'status',
        'register_date',
        'last_visit',
        'total_tests'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'register_date' => 'date',
        'last_visit' => 'date',
    ];

    // Relasi ke urine tests (sesuaikan dengan nama tabel Anda)
    public function urineTests()
    {
        return $this->hasMany(UrineTest::class);
    }

    // Generate patient ID otomatis
    public static function generatePatientId()
    {
        $year = date('Y');
        $lastPatient = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPatient) {
            $lastNumber = (int) substr($lastPatient->patient_id, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return "PAT-{$year}-{$newNumber}";
    }

    // Hitung umur otomatis dari birthdate
    public function calculateAge()
    {
        return \Carbon\Carbon::parse($this->birthdate)->age;
    }
}