<?php
namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nouveau message reçu')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez reçu un nouveau message de ' . $this->message->sender->name . '.')
            ->line('Sujet : ' . $this->message->subject)
            ->action('Voir le message', route('messages.show', $this->message))
            ->line('Merci d’utiliser la plateforme Suivi Scolaire.');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message_id' => $this->message->id,
            'subject' => $this->message->subject,
            'sender' => $this->message->sender->name,
        ]);
    }
} 