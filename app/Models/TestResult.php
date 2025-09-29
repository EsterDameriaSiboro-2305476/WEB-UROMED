<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'patient_id',
        'user_id',
        'test_date',
        'status',
        'result',
        'notes'
    ];

    protected $casts = [
        'test_date' => 'date',
    ];

    // Relasi ke patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Relasi ke user (yang melakukan tes)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke test results
    public function results()
    {
        return $this->hasMany(TestResult::class);
    }

    // Generate test ID otomatis
    public static function generateTestId()
    {
        $year = date('Y');
        $lastTest = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTest) {
            $lastNumber = (int) substr($lastTest->test_id, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "UR-{$year}-{$newNumber}";
    }
}

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'parameter_name',
        'value',
        'unit',
        'reference_range',
        'status'
    ];

    // Relasi ke test
    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}