<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // admiN user
        User::create([
            'name' => 'Dr. Admin UroMed',
            'email' => 'admin@uromed.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        //operator
        User::create([
            'name' => 'Operator UroMed',
            'email' => 'operator@uromed.com', 
            'password' => Hash::make('password123'),
            'email_verified_at' => now()
        ]);

        echo "✅ Database seeding completed!\n";
        echo "📧 Admin Email: admin@uromed.com\n"; 
        echo "🔑 Password: password123\n\n";
        echo "📧 Operator Email: operator@uromed.com\n";
        echo "🔑 Password: password123\n\n";
    }
}