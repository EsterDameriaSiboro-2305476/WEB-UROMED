<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_result_id', 'summary', 'detailed_analysis', 'recommendations',
        'confidence_score', 'risk_level', 'potential_conditions'
    ];

    protected $casts = [
        'detailed_analysis' => 'array',
        'potential_conditions' => 'array',
        'confidence_score' => 'decimal:2'
    ];

    // Relationships
    public function testResult()
    {
        return $this->belongsTo(TestResult::class);
    }

    // Accessor untuk risk level color
    // public function getRiskLevelColorAttribute()
    // {
    //     return match($this->risk_level) {
    //         'low' => 'green',
    //         'medium' => 'yellow',
    //         'high' => 'red',
    //         // default => 'gray'
    //     };
    // }

    // Accessor untuk confidence level
    public function getConfidenceLevelAttribute()
    {
        if ($this->confidence_score >= 90) return 'Sangat Tinggi';
        if ($this->confidence_score >= 75) return 'Tinggi';
        if ($this->confidence_score >= 60) return 'Sedang';
        return 'Rendah';
    }
}
