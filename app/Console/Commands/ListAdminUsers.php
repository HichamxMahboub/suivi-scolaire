<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListAdminUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list-admins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lister tous les utilisateurs administrateurs du système';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('Aucun administrateur trouvé dans le système.');
            return 0;
        }

        $this->info("📋 Liste des administrateurs ({$admins->count()} trouvé(s)):");
        $this->newLine();

        $headers = ['ID', 'Nom', 'Email', 'Rôle', 'Créé le', 'Vérifié'];
        $rows = [];

        foreach ($admins as $admin) {
            $rows[] = [
                $admin->id,
                $admin->name,
                $admin->email,
                $admin->role,
                $admin->created_at->format('d/m/Y H:i'),
                $admin->email_verified_at ? '✅' : '❌',
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info('💡 Pour créer un nouvel administrateur: php artisan user:create-root email@example.com password');
        $this->info('💡 Pour voir tous les utilisateurs: php artisan user:list-all');

        return 0;
    }
}
