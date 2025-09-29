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
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_result_id');
            $table->text('summary');
            $table->longText('detailed_analysis');
            $table->text('recommendations');
            $table->decimal('confidence_score', 5, 2);
            $table->enum('risk_level', ['low', 'medium', 'high']);
            $table->longText('potential_conditions')->nullable();
            $table->timestamps();
            
            // Add foreign key constraint if needed
            // $table->foreign('test_result_id')->references('id')->on('test_results')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analyses');
    }
};