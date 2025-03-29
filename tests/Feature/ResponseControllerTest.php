<?php
namespace Tests\Feature;

use App\Models\Response;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseControllerTest extends TestCase
{
    use RefreshDatabase;

    
public function it_can_store_a_response()
{
    
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum'); 
    
    $ticket = Ticket::factory()->create();

    $responseData = [
        'response' => 'This is a response to the ticket.',
        'ticket_id' => $ticket->id,
        'user_id' => $user->id,
    ];

    $this->postJson('/api/responses', $responseData)
        ->assertStatus(201)
        ->assertJsonFragment(['response' => 'This is a response to the ticket.']);
}

   /** @test */
public function it_can_get_responses_by_ticket_id()
{
  
    $user = User::factory()->create();
    $this->actingAs($user, 'sanctum'); 
    
    $ticket = Ticket::factory()->create();
    $response = Response::factory()->create(['ticket_id' => $ticket->id]);

    
    $this->getJson("/api/responses/ticket/{$ticket->id}")
        ->assertStatus(200)
        ->assertJsonFragment(['response' => $response->response]);
}

}
