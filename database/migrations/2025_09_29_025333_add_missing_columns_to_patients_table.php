<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Menambahkan kolom yang hilang dengan nullable untuk data existing
            $table->date('birthdate')->nullable()->after('name'); // Nullable untuk data existing
            $table->string('email')->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
            $table->datetime('register_date')->nullable()->after('medical_history');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('register_date');
            $table->integer('total_tests')->default(0)->after('status');
        });

        // Update data existing dengan nilai default
        DB::table('patients')->update([
            'birthdate' => '1990-01-01', // Default birthdate
            'register_date' => now(),
            'status' => 'active',
            'total_tests' => 0
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'birthdate', 
                'email', 
                'address', 
                'register_date', 
                'status', 
                'total_tests'
            ]);
        });
    }
};