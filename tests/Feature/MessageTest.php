<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use App\Models\Eleve;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $recipient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'admin']);
        $this->recipient = User::factory()->create(['role' => 'teacher']);
    }

    /** @test */
    public function it_can_display_messages_index()
    {
        $response = $this->actingAs($this->user)->get('/messages');
        $response->assertStatus(200);
        $response->assertViewIs('messages.index');
    }

    /** @test */
    public function it_can_display_sent_messages()
    {
        $response = $this->actingAs($this->user)->get('/messages/sent');
        $response->assertStatus(200);
        $response->assertViewIs('messages.sent');
    }

    /** @test */
    public function it_can_create_message()
    {
        $eleve = Eleve::factory()->create();

        $messageData = [
            'recipient_id' => $this->recipient->id,
            'subject' => 'Test Message',
            'content' => 'This is a test message content.',
            'priority' => 'normal',
            'type' => 'general',
            'eleve_id' => $eleve->id,
        ];

        $response = $this->actingAs($this->user)->post('/messages', $messageData);
        $response->assertRedirect('/messages');
        $this->assertDatabaseHas('messages', ['subject' => 'Test Message']);
    }

    /** @test */
    public function it_can_send_message_with_attachments()
    {
        Storage::fake('public');
        
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $messageData = [
            'recipient_id' => $this->recipient->id,
            'subject' => 'Message with attachment',
            'content' => 'Message content',
            'priority' => 'high',
            'type' => 'academic',
            'attachments' => [$file],
        ];

        $response = $this->actingAs($this->user)->post('/messages', $messageData);
        $response->assertRedirect('/messages');
        
        $message = Message::where('subject', 'Message with attachment')->first();
        $this->assertNotNull($message);
        $this->assertEquals(1, $message->attachments()->count());
    }

    /** @test */
    public function it_can_view_message()
    {
        $message = Message::factory()->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $this->recipient->id,
        ]);

        $response = $this->actingAs($this->user)->get("/messages/{$message->id}");
        $response->assertStatus(200);
        $response->assertViewIs('messages.show');
    }

    /** @test */
    public function it_can_mark_message_as_read()
    {
        $message = Message::factory()->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $this->recipient->id,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->recipient)->patch("/messages/{$message->id}/read");
        $response->assertRedirect();
        
        $this->assertNotNull($message->fresh()->read_at);
    }

    /** @test */
    public function it_can_delete_message()
    {
        $message = Message::factory()->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $this->recipient->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/messages/{$message->id}");
        $response->assertRedirect('/messages');
        $this->assertSoftDeleted('messages', ['id' => $message->id]);
    }

    /** @test */
    public function it_can_display_messages_by_eleve()
    {
        $eleve = Eleve::factory()->create();
        $response = $this->actingAs($this->user)->get("/messages/eleve/{$eleve->id}");
        $response->assertStatus(200);
        $response->assertViewIs('messages.by-eleve');
    }

    /** @test */
    public function it_can_archive_message()
    {
        $message = Message::factory()->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $this->recipient->id,
        ]);

        $response = $this->actingAs($this->user)->post("/messages/{$message->id}/archive");
        $response->assertRedirect();
        
        $this->assertNotNull($message->fresh()->archived_at);
    }

    /** @test */
    public function it_can_restore_archived_message()
    {
        $message = Message::factory()->create([
            'sender_id' => $this->user->id,
            'recipient_id' => $this->recipient->id,
            'archived_at' => now(),
        ]);

        $response = $this->actingAs($this->user)->post("/messages/{$message->id}/restore");
        $response->assertRedirect();
        
        $this->assertNull($message->fresh()->archived_at);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->actingAs($this->user)->post('/messages', []);
        $response->assertSessionHasErrors(['recipient_id', 'subject', 'content', 'priority', 'type']);
    }

    /** @test */
    public function it_validates_recipient_exists()
    {
        $response = $this->actingAs($this->user)->post('/messages', [
            'recipient_id' => 99999, // Non-existent
            'subject' => 'Test',
            'content' => 'Content',
            'priority' => 'normal',
            'type' => 'general',
        ]);

        $response->assertSessionHasErrors(['recipient_id']);
    }

    /** @test */
    public function it_validates_priority_values()
    {
        $response = $this->actingAs($this->user)->post('/messages', [
            'recipient_id' => $this->recipient->id,
            'subject' => 'Test',
            'content' => 'Content',
            'priority' => 'invalid', // Invalid priority
            'type' => 'general',
        ]);

        $response->assertSessionHasErrors(['priority']);
    }

    /** @test */
    public function it_validates_message_type()
    {
        $response = $this->actingAs($this->user)->post('/messages', [
            'recipient_id' => $this->recipient->id,
            'subject' => 'Test',
            'content' => 'Content',
            'priority' => 'normal',
            'type' => 'invalid', // Invalid type
        ]);

        $response->assertSessionHasErrors(['type']);
    }

    /** @test */
    public function it_can_get_unread_count()
    {
        Message::factory()->count(3)->create([
            'recipient_id' => $this->user->id,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->user)->get('/messages/unread-count');
        $response->assertStatus(200);
        $response->assertJson(['count' => 3]);
    }
} 