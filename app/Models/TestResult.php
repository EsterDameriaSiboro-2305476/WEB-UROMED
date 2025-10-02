<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $table = 'test_results';

    protected $fillable = [
        'urine_test_id',
        'ph_level',
        'protein',
        'glucose',
        'color',
        'temperature',
        'volume',
        'specific_gravity',
        'raw_sensor_data',
        'overall_status',
    ];

    protected $casts = [
        'raw_sensor_data' => 'array',
    ];

    // Relasi ke tabel urine_tests
    public function urineTest()
    {
        return $this->belongsTo(UrineTest::class, 'urine_test_id');
    }
}
