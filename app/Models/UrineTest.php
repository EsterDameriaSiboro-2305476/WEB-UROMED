<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UrineTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'test_id',
        'patient_id',
        'test_date',
        'ph_level',
        'ph_status',
        'protein',
        'protein_status',
        'glucose',
        'glucose_status',
        'ketones',
        'ketones_status',
        'blood',
        'blood_status',
        'color',
        'color_status',
        'clarity',
        'clarity_status',
        'specific_gravity',
        'gravity_status',
        'result_status',
        'notes',
    ];

    protected $casts = [
        'test_date' => 'datetime',
        'ph_level' => 'decimal:2',
        'specific_gravity' => 'decimal:3',
    ];

    /**
     * Relationship with Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeNormal($query)
    {
        return $query->where('result_status', 'Normal');
    }

    public function scopePerhatian($query)
    {
        return $query->where('result_status', 'Perhatian');
    }

    public function scopeAbnormal($query)
    {
        return $query->where('result_status', 'Abnormal');
    }

    /**
     * Scope untuk filter berdasarkan tanggal
     */
    public function scopeToday($query)
    {
        return $query->whereDate('test_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('test_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('test_date', now()->month)
                    ->whereYear('test_date', now()->year);
    }

    
    public function getFormattedTestDateAttribute()
    {
        return $this->test_date->format('d/m/Y H:i');
    }

    /**
     * Check if test has any abnormal results
     */
    public function hasAbnormalResults()
    {
        $statuses = [
            $this->ph_status,
            $this->protein_status,
            $this->glucose_status,
            $this->ketones_status,
            $this->blood_status,
            $this->color_status,
            $this->clarity_status,
            $this->gravity_status,
        ];

        return in_array('Abnormal', $statuses);
    }

    /**
     * Get count of abnormal parameters
     */
    public function getAbnormalCount()
    {
        $statuses = [
            $this->ph_status,
            $this->protein_status,
            $this->glucose_status,
            $this->ketones_status,
            $this->blood_status,
            $this->color_status,
            $this->clarity_status,
            $this->gravity_status,
        ];

        return count(array_filter($statuses, function($status) {
            return $status === 'Abnormal';
        }));
    }
}