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
        // User::factory(10)->create();

        // Création d'un compte root
        User::create([
            'name' => 'Root',
            'email' => 'root@example.com',
            'password' => Hash::make('root1234'), // Change ce mot de passe après le premier login !
            'role' => 'root',
        ]);

        \App\Models\User::firstOrCreate([
            'email' => 'root@admin.com'
        ], [
            'name' => 'Root Principal',
            'password' => bcrypt('admin1234'),
            'role' => 'admin'
        ]);

        // Insérer les données des élèves
        $this->call([
            ClasseSeeder::class,
            ClassesMarocainesSeeder::class,
            MessageSeeder::class,
            UpdateNiveauxScolairesSeeder::class,
            EleveSeeder::class, // Ajout du seeder des élèves
        ]);
    }
}
