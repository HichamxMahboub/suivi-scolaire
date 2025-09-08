<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Message;

class MessagePolicy
{
    public function view(User $user, Message $message)
    {
        return $user->id === $message->sender_id || $user->id === $message->recipient_id;
    }
    public function delete(User $user, Message $message)
    {
        return $user->id === $message->sender_id || $user->id === $message->recipient_id;
    }
    public function archive(User $user, Message $message)
    {
        return $user->id === $message->sender_id || $user->id === $message->recipient_id;
    }
    public function restore(User $user, Message $message)
    {
        return $user->id === $message->sender_id || $user->id === $message->recipient_id;
    }
}
