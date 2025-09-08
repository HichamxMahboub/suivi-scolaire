<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Eleve;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Notifications\NewMessageNotification;

class MessageController extends Controller
{
    /**
     * Afficher la boîte de réception
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');
        $type = $request->get('type', '');
        $priority = $request->get('priority', '');

        $messages = $user->receivedMessages()
            ->with(['sender', 'eleve'])
            ->when($filter === 'unread', function ($query) {
                return $query->unread();
            })
            ->when($filter === 'read', function ($query) {
                return $query->read();
            })
            ->when($filter === 'favorite', function ($query) use ($user) {
                return $query->whereIn('id', $user->favorites->pluck('id'));
            })
            ->when($filter === 'archived', function ($query) {
                return $query->archived();
            })
            ->when($type, function ($query) use ($type) {
                return $query->byType($type);
            })
            ->when($priority, function ($query) use ($priority) {
                return $query->byPriority($priority);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $unreadCount = $user->unreadMessages()->count();
        $users = User::where('id', '!=', $user->id)->orderBy('name')->get();
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();

        return view('messages.index', compact('messages', 'unreadCount', 'users', 'eleves', 'filter', 'type', 'priority'));
    }

    /**
     * Afficher les messages envoyés
     */
    public function sent(): View
    {
        $user = Auth::user();
        $messages = $user->sentMessages()
            ->with(['recipient', 'eleve'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.sent', compact('messages'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request): View
    {
        $user = Auth::user();
        $users = User::where('id', '!=', $user->id)->orderBy('name')->get();
        $eleves = Eleve::orderBy('nom')->orderBy('prenom')->get();
        $classes = Classe::orderBy('nom')->get();
        $selectedRecipient = $request->get('recipient');
        $selectedEleve = $request->get('eleve');

        // Préparation des données parents pour le JS
        $elevesData = $eleves->map(function ($eleve) {
            return [
                'id' => $eleve->id,
                'parent1' => [
                    'nom' => $eleve->nom_parent_1,
                    'lien' => $eleve->lien_parent_1,
                    'telephones' => $eleve->telephones_parent_1,
                ],
                'parent2' => [
                    'nom' => $eleve->nom_parent_2,
                    'lien' => $eleve->lien_parent_2,
                    'telephones' => $eleve->telephones_parent_2,
                ]
            ];
        })->values()->all();

        return view('messages.create', compact('users', 'eleves', 'classes', 'selectedRecipient', 'selectedEleve', 'elevesData'));
    }

    /**
     * Envoyer un nouveau message
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:low,normal,high,urgent',
            'type' => 'required|in:general,academic,behavior,health,parent_contact,other',
            'eleve_id' => 'nullable|exists:eleves,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'eleve_id' => $request->eleve_id,
            'subject' => $request->subject,
            'content' => $request->content,
            'priority' => $request->priority,
            'type' => $request->type,
        ]);

        // Gestion des pièces jointes
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // --- HOOK SCAN ANTIVIRUS ---
                // Exemple d'intégration ClamAV (ou API externe) :
                // if (!app('clamav')->scan($file->getRealPath())) {
                //     return redirect()->back()->with('error', 'Fichier infecté détecté.');
                // }
                // Pour une API externe, envoyer le fichier et vérifier la réponse avant de stocker
                // --- FIN HOOK ---
                $path = $file->store('message_attachments', 'public');
                $message->attachments()->create([
                    'filename' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        // Notification au destinataire
        $recipient = User::find($request->recipient_id);
        if ($recipient) {
            $recipient->notify(new NewMessageNotification($message));
        }

        return redirect()->route('messages.index')
            ->with('success', 'Message envoyé avec succès.');
    }

    /**
     * Afficher un message
     */
    public function show(Message $message): View
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur peut voir ce message
        $this->authorize('view', $message);

        // Marquer comme lu si c'est le destinataire
        if ($message->recipient_id === $user->id && $message->isUnread()) {
            $message->markAsRead();
        }

        return view('messages.show', compact('message'));
    }

    /**
     * Marquer un message comme lu
     */
    public function markAsRead(Message $message): RedirectResponse
    {
        $user = Auth::user();

        if ($message->recipient_id === $user->id) {
            $message->markAsRead();
            return redirect()->back()->with('success', 'Message marqué comme lu.');
        }

        return redirect()->back()->with('error', 'Action non autorisée.');
    }

    /**
     * Marquer plusieurs messages comme lus
     */
    public function markMultipleAsRead(Request $request): RedirectResponse
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id'
        ]);

        $user = Auth::user();
        $messages = Message::whereIn('id', $request->message_ids)
            ->where('recipient_id', $user->id)
            ->get();

        foreach ($messages as $message) {
            $message->markAsRead();
        }

        return redirect()->back()->with('success', count($messages) . ' message(s) marqué(s) comme lu(s).');
    }

    /**
     * Supprimer un message
     */
    public function destroy(Message $message): RedirectResponse
    {
        $user = Auth::user();

        $this->authorize('delete', $message);

        if ($message->sender_id === $user->id || $message->recipient_id === $user->id) {
            $message->delete();
            return redirect()->route('messages.index')->with('success', 'Message supprimé avec succès.');
        }

        return redirect()->back()->with('error', 'Action non autorisée.');
    }

    /**
     * Afficher les messages par élève
     */
    public function byEleve(Eleve $eleve): View
    {
        $user = Auth::user();
        $messages = Message::where('eleve_id', $eleve->id)
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                      ->orWhere('recipient_id', $user->id);
            })
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('messages.by-eleve', compact('messages', 'eleve'));
    }

    /**
     * Afficher les statistiques des messages
     */
    public function stats(): View
    {
        $user = Auth::user();

        $stats = [
            'total_received' => $user->receivedMessages()->count(),
            'unread_received' => $user->unreadMessages()->count(),
            'total_sent' => $user->sentMessages()->count(),
            'by_type' => $user->receivedMessages()
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'by_priority' => $user->receivedMessages()
                ->selectRaw('priority, count(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority'),
        ];

        return view('messages.stats', compact('stats'));
    }

    /**
     * Réponse rapide à un message (AJAX)
     */
    public function quickReply(Request $request, Message $message)
    {
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        // Le destinataire de la réponse est l'expéditeur du message d'origine
        $reply = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $message->sender_id,
            'subject' => 'RE: ' . $message->subject,
            'content' => $request->content,
            'priority' => $message->priority,
            'type' => $message->type,
            'eleve_id' => $message->eleve_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Réponse envoyée avec succès.',
            'reply' => [
                'id' => $reply->id,
                'content' => $reply->content,
                'created_at' => $reply->created_at->format('d/m/Y H:i'),
            ]
        ]);
    }

    public function toggleFavorite(Request $request, Message $message)
    {
        $user = Auth::user();
        $isFavorite = $user->favorites()->where('message_id', $message->id)->exists();
        if ($isFavorite) {
            $user->favorites()->detach($message->id);
        } else {
            $user->favorites()->attach($message->id);
        }
        if ($request->ajax()) {
            return response()->json(['favorited' => !$isFavorite]);
        }
        return back();
    }

    public function archive(Request $request, Message $message)
    {
        $user = Auth::user();
        $this->authorize('archive', $message);
        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }
        if ($request->has('confirm')) {
            $message->archive();
            return redirect()->route('messages.index')->with('success', 'Message archivé.');
        }
        return redirect()->back()->with('error', 'Confirmation requise pour archiver.');
    }

    public function restoreArchive(Request $request, Message $message)
    {
        $user = Auth::user();
        $this->authorize('restore', $message);
        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            return redirect()->back()->with('error', 'Action non autorisée.');
        }
        $message->restoreArchive();
        return redirect()->route('messages.index')->with('success', 'Message restauré.');
    }
}
