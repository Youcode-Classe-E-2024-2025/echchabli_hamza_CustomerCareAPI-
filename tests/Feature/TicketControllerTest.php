<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_create_ticket()
    {
        $response = $this->postJson('/api/tickets', [
            'title' => 'Test Ticket',
            'description' => 'This is a test ticket.',
            'owner_id' => $this->user->id
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => ' created successfully',
            ]);

        $this->assertDatabaseHas('tickets', ['title' => 'Test Ticket']);
    }

    public function test_get_ticket_by_id()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->getJson("/api/tickets/{$ticket->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Ticket retrieved successfully']);
    }

    public function test_get_ticket_not_found()
    {
        $response = $this->getJson('/api/tickets/9999');

        $response->assertStatus(404)
            ->assertJson(['message' => 'Ticket not found']);
    }

    public function test_assign_ticket_to_agent()
    {
        $ticket = Ticket::factory()->create();
        $agent = User::factory()->create();

        $response = $this->postJson("/api/tickets/{$ticket->id}/assign", [
            'agent_id' => $agent->id
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Ticket assigned to agent successfully']);

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'agent_id' => $agent->id]);
    }

    public function test_update_ticket_status()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->putJson("/api/tickets/{$ticket->id}/status", [
            'status' => 'closed'
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Ticket status updated successfully']);

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'closed']);
    }

    public function test_update_ticket_progress()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->putJson("/api/tickets/{$ticket->id}/progress", [
            'progress' => 'done'
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Ticket progress updated successfully']);

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'progress' => 'done']);
    }

    public function test_get_client_tickets()
    {
        Ticket::factory()->create(['owner_id' => $this->user->id]);

        $response = $this->getJson("/api/tickets/client/{$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['tickets']);
    }

    public function test_get_agent_tickets()
    {
        $agent = User::factory()->create();
        Ticket::factory()->create(['agent_id' => $agent->id]);

        $response = $this->getJson("/api/tickets/agent/{$agent->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['tickets']);
    }

    public function test_get_all_tickets_with_pagination()
    {
        Ticket::factory(5)->create();

        $response = $this->getJson('/api/tickets?per_page=2');

        $response->assertStatus(200)
            ->assertJsonStructure(['tickets' => ['data', 'current_page', 'last_page']]);
    }
}
