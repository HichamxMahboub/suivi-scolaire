<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RootUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un compte administrateur root
        User::create([
            'name' => 'Root Administrator',
            'email' => 'root@admin.com',
            'password' => Hash::make('root123456'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Créer un compte administrateur de test
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Créer un compte enseignant de test
        User::create([
            'name' => 'Enseignant Test',
            'email' => 'enseignant@test.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Comptes de test créés avec succès !');
        $this->command->info('Root Admin: root@admin.com / root123456');
        $this->command->info('Admin Test: admin@test.com / admin123');
        $this->command->info('Enseignant Test: enseignant@test.com / teacher123');
    }
}
