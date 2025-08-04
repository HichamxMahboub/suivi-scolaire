<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations avec les messages
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function unreadMessages()
    {
        return $this->receivedMessages()->unread();
    }

    public function favorites()
    {
        return $this->belongsToMany(Message::class, 'favorite_messages')->withTimestamps();
    }

    // Méthodes pour les rôles
    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'root';
    }

    public function isEncadrant()
    {
        return $this->role === 'encadrant';
    }

    public function isMedical()
    {
        return $this->role === 'medical';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function hasAnyRole($roles)
    {
        return in_array($this->role, (array) $roles);
    }

    // Permissions spécifiques
    public function canViewMedicalInfo()
    {
        return $this->isAdmin() || $this->isMedical();
    }

    public function canEditMedicalInfo()
    {
        return $this->isAdmin() || $this->isMedical();
    }

    public function canManageNotes()
    {
        return $this->isAdmin() || $this->isEncadrant() || $this->isTeacher();
    }

    public function canViewFullProfile()
    {
        return $this->isAdmin();
    }

    public function canSendMessage()
    {
        return true; // Tous les utilisateurs connectés peuvent envoyer des messages
    }

    public function canManageUsers()
    {
        return $this->isAdmin();
    }
}
