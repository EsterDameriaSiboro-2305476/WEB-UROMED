<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UroMedSetup extends Command
{
    protected $signature = 'uromed:setup {--force : Force setup even if already configured}';
    protected $description = 'Setup UroMed dashboard with database and sample data';

    public function handle()
    {
        $this->info('🚀 Setting up UroMed Dashboard...');
        
        // Check if already setup
        if (!$this->option('force') && \App\Models\User::count() > 0) {
            if (!$this->confirm('Database already contains data. Continue anyway?')) {
                $this->info('Setup cancelled.');
                return;
            }
        }

        $this->info('📊 Running database migrations...');
        Artisan::call('migrate:fresh');
        $this->info('✅ Migrations completed');

        $this->info('🌱 Seeding sample data...');
        Artisan::call('db:seed');
        $this->info('✅ Sample data created');

        $this->info('🔧 Clearing caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        $this->info('✅ Caches cleared');

        $this->newLine();
        $this->info('🎉 UroMed Dashboard setup completed successfully!');
        $this->newLine();
        
        $this->table(
            ['Account', 'Email', 'Password'],
            [
                ['Admin', 'admin@uromed.com', 'password123'],
                ['Operator', 'operator@uromed.com', 'password123']
            ]
        );

        $this->newLine();
        $this->info('🌐 You can now access the dashboard at: ' . config('app.url'));
        $this->info('📡 Sensor API endpoint: ' . config('app.url') . '/api/v1/sensor/data');
    }
}