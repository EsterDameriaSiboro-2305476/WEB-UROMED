<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('urine_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_id')->unique(); // UR-2025-0001
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->datetime('test_date'); // Tanggal dan waktu tes
            
            // Physical parameters
            $table->string('color')->nullable(); // Warna urin
            $table->string('color_status')->default('Normal');
            $table->string('clarity')->nullable(); // Kejernihan
            $table->string('clarity_status')->default('Normal');
            
            // Chemical parameters
            $table->decimal('ph_level', 3, 1)->nullable(); // pH (0.0-14.0)
            $table->string('ph_status')->default('Normal');
            $table->decimal('specific_gravity', 5, 3)->nullable(); // Berat jenis (1.000-1.050)
            $table->string('gravity_status')->default('Normal');
            
            // Biochemical parameters
            $table->string('protein')->nullable(); // Negatif/Trace/Positif
            $table->string('protein_status')->default('Normal');
            $table->string('glucose')->nullable(); // Negatif/Positif
            $table->string('glucose_status')->default('Normal');
            $table->string('ketones')->nullable(); // Negatif/Positif
            $table->string('ketones_status')->default('Normal');
            $table->string('blood')->nullable(); // Negatif/Positif
            $table->string('blood_status')->default('Normal');
            
            // Additional parameters (optional)
            $table->string('bilirubin')->nullable();
            $table->string('bilirubin_status')->default('Normal');
            $table->string('urobilinogen')->nullable();
            $table->string('urobilinogen_status')->default('Normal');
            $table->string('nitrite')->nullable();
            $table->string('nitrite_status')->default('Normal');
            $table->string('leukocyte_esterase')->nullable();
            $table->string('leukocyte_status')->default('Normal');
            
            // Overall result
            $table->enum('result_status', ['Normal', 'Perhatian', 'Abnormal'])->default('Normal');
            $table->text('notes')->nullable(); // Catatan tambahan
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('test_id');
            $table->index('patient_id');
            $table->index('test_date');
            $table->index('result_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urine_tests');
    }
};