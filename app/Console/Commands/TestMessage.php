<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Message;

class TestMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test message to verify messaging system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get admin and encadrant users
        $admin = User::where('email', 'admin@ecole.ma')->first();
        $encadrant = User::where('email', 'marie.dubois@ecole.ma')->first();
        
        if (!$admin || !$encadrant) {
            $this->error('Users not found. Please ensure admin and encadrant users exist.');
            return;
        }

        // Create a test message from admin to encadrant
        $message = Message::create([
            'sender_id' => $admin->id,
            'recipient_id' => $encadrant->id,
            'subject' => 'Message de test - Système de messagerie',
            'content' => 'Ceci est un message de test pour vérifier que le système de messagerie fonctionne correctement. Le rôle de l\'expéditeur est : ' . $admin->role . ' et le destinataire est : ' . $encadrant->role,
            'type' => 'general',
            'priority' => 'normal'
        ]);

        // Create a reply from encadrant to admin
        $reply = Message::create([
            'sender_id' => $encadrant->id,
            'recipient_id' => $admin->id,
            'subject' => 'Re: Message de test - Système de messagerie',
            'content' => 'Merci pour le message de test. Le système de messagerie fonctionne bien. Mon rôle d\'encadrant me permet d\'envoyer des messages.',
            'type' => 'general',
            'priority' => 'normal'
        ]);

        $this->info('✅ Messages de test créés avec succès !');
        $this->info('📧 Message 1: Admin → Encadrant (ID: ' . $message->id . ')');
        $this->info('📧 Message 2: Encadrant → Admin (ID: ' . $reply->id . ')');
        
        // Show current message count
        $totalMessages = Message::count();
        $this->info('📊 Total des messages dans la base : ' . $totalMessages);
        
        return Command::SUCCESS;
    }
}
