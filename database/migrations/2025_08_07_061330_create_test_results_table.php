<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urine_test_id')->constrained()->onDelete('cascade');
            
            // Data dari sensor
            $table->decimal('ph_level', 3, 2); // pH 0.00-14.00
            $table->decimal('protein', 5, 2); // mg/dL
            $table->decimal('glucose', 5, 2); // mg/dL  
            $table->string('color'); // Warna urin
            $table->decimal('temperature', 4, 2); // Suhu Celsius
            $table->integer('volume'); // Volume dalam mL
            $table->decimal('specific_gravity', 4, 3); // Berat jenis
            
            // Metadata
            $table->json('raw_sensor_data')->nullable(); // Data mentah dari sensor
            $table->enum('overall_status', ['normal', 'abnormal', 'needs_review']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};
