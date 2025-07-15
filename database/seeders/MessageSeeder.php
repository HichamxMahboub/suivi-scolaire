<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Message;
use App\Models\Eleve;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des utilisateurs de test avec différents rôles
        $users = [
            [
                'name' => 'Admin Principal',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Professeur Mathématiques',
                'email' => 'math@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
            [
                'name' => 'Professeur Français',
                'email' => 'francais@example.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ],
            [
                'name' => 'Conseiller Principal',
                'email' => 'conseiller@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Récupérer les utilisateurs créés
        $admin = User::where('email', 'admin@example.com')->first();
        $profMath = User::where('email', 'math@example.com')->first();
        $profFrancais = User::where('email', 'francais@example.com')->first();
        $conseiller = User::where('email', 'conseiller@example.com')->first();

        // Récupérer quelques élèves pour les exemples
        $eleves = Eleve::take(3)->get();

        // Créer des messages d'exemple
        $messages = [
            [
                'sender_id' => $profMath->id,
                'recipient_id' => $admin->id,
                'eleve_id' => $eleves->first()?->id,
                'subject' => 'Problème de comportement en cours',
                'content' => "Bonjour,\n\nJe souhaite vous informer d'un incident survenu aujourd'hui en cours de mathématiques avec l'élève concerné. Il a eu un comportement perturbateur qui a nécessité une intervention.\n\nMerci de me contacter pour en discuter.\n\nCordialement,",
                'priority' => 'high',
                'type' => 'behavior',
            ],
            [
                'sender_id' => $admin->id,
                'recipient_id' => $profFrancais->id,
                'subject' => 'Réunion pédagogique',
                'content' => "Bonjour,\n\nJe vous convie à une réunion pédagogique qui aura lieu vendredi prochain à 14h00 en salle des professeurs.\n\nOrdre du jour :\n- Bilan du premier trimestre\n- Préparation des examens\n- Questions diverses\n\nMerci de confirmer votre présence.\n\nCordialement,",
                'priority' => 'normal',
                'type' => 'general',
            ],
            [
                'sender_id' => $conseiller->id,
                'recipient_id' => $profMath->id,
                'eleve_id' => $eleves->skip(1)->first()?->id,
                'subject' => 'Suivi académique - Demande de rendez-vous',
                'content' => "Bonjour,\n\nJe souhaite organiser un rendez-vous pour discuter du suivi académique de l'élève concerné. Il semble avoir des difficultés dans votre matière.\n\nPouvez-vous me proposer quelques créneaux horaires ?\n\nMerci.\n\nCordialement,",
                'priority' => 'normal',
                'type' => 'academic',
            ],
            [
                'sender_id' => $profFrancais->id,
                'recipient_id' => $conseiller->id,
                'eleve_id' => $eleves->last()?->id,
                'subject' => 'Progrès remarquables',
                'content' => "Bonjour,\n\nJe tenais à vous informer des excellents progrès réalisés par l'élève concerné en français. Son niveau d'expression écrite s'est considérablement amélioré.\n\nC'est très encourageant pour la suite de son parcours.\n\nCordialement,",
                'priority' => 'low',
                'type' => 'academic',
            ],
            [
                'sender_id' => $admin->id,
                'recipient_id' => $profMath->id,
                'subject' => 'Urgent - Absence de professeur',
                'content' => "Bonjour,\n\nEn raison d'une absence imprévue, pouvez-vous assurer le cours de mathématiques de 6ème A demain matin de 8h à 10h ?\n\nMerci de me confirmer rapidement.\n\nCordialement,",
                'priority' => 'urgent',
                'type' => 'general',
            ],
        ];

        foreach ($messages as $messageData) {
            Message::create($messageData);
        }

        // Marquer quelques messages comme lus
        Message::where('recipient_id', $admin->id)->take(2)->update(['read_at' => now()]);
        Message::where('recipient_id', $profMath->id)->take(1)->update(['read_at' => now()]);
    }
} 