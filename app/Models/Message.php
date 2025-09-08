<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'eleve_id',
        'subject',
        'content',
        'priority',
        'type',
        'status',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    protected $dates = ['archived_at', 'deleted_at'];

    // Relations
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function eleve()
    {
        return $this->belongsTo(Eleve::class);
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByEleve($query, $eleveId)
    {
        return $query->where('eleve_id', $eleveId);
    }

    // Méthodes
    public function markAsRead()
    {
        $this->update([
            'read_at' => now(),
            'status' => 'read'
        ]);
    }

    public function markAsDelivered()
    {
        $this->update(['status' => 'delivered']);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isUnread()
    {
        return is_null($this->read_at);
    }

    public function isFavoritedBy($user)
    {
        return $user && $user->favorites->contains($this->id);
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'normal' => 'blue',
            'low' => 'gray',
            default => 'blue',
        };
    }

    public function getTypeIconAttribute()
    {
        return match($this->type) {
            'academic' => '📚',
            'behavior' => '⚠️',
            'health' => '🏥',
            'parent_contact' => '👨‍👩‍👧‍👦',
            'general' => '💬',
            'other' => '📝',
            default => '💬',
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'read' => 'green',
            'delivered' => 'blue',
            'sent' => 'gray',
            default => 'gray',
        };
    }

    // Méthode pour obtenir les informations des parents si le message concerne un élève
    public function getParentInfoAttribute()
    {
        if (!$this->eleve) {
            return null;
        }

        return [
            'parent1' => [
                'nom' => $this->eleve->nom_parent_1,
                'lien' => $this->eleve->lien_parent_1,
                'telephones' => $this->eleve->telephones_parent_1,
            ],
            'parent2' => [
                'nom' => $this->eleve->nom_parent_2,
                'lien' => $this->eleve->lien_parent_2,
                'telephones' => $this->eleve->telephones_parent_2,
            ]
        ];
    }

    public function archive()
    {
        $this->archived_at = now();
        $this->save();
    }

    public function restoreArchive()
    {
        $this->archived_at = null;
        $this->save();
    }

    public function isArchived()
    {
        return !is_null($this->archived_at);
    }

    public function scopeArchived($query)
    {
        return $query->whereNotNull('archived_at');
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = Crypt::encryptString($value);
    }

    public function getContentAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value; // fallback si non chiffré
        }
    }
}
