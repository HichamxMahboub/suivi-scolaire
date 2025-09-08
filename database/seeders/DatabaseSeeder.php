<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Remove old root and admin users
        User::whereIn('role', ['root', 'admin'])->delete();

        // Create or update single administrator user
        User::updateOrCreate(
            ['email' => 'admin@ecole.ma'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Insérer les données des élèves
        $this->call([
            ClasseSeeder::class,
            ClassesMarocainesSeeder::class,
            MessageSeeder::class,
            UpdateNiveauxScolairesSeeder::class,
            EleveSeeder::class, // Ajout du seeder des élèves
            // NoteSeeder removed: does not exist
        ]);
    }
}
