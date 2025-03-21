<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use App\Services\ActivitieService;
use App\Http\Requests\ticketStoreRequest;
use Illuminate\Http\Request;



class TicketController extends Controller
{
    protected $ticketService;
    protected $activityService;

    public function __construct(TicketService $ticketService , ActivitieService $activityService)
    {
        $this->ticketService = $ticketService;
        $this->activityService = $activityService;
        
    }

    
    public function store(ticketStoreRequest $request)
    {
        // $data = $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'required|string',
        //     'owner_id' => 'required|exists:users,id',
        // ]);

        $data = $request->validated();

        $ticket = $this->ticketService->createTicket($data);

        $this->activityService->addActivity($ticket->id, $request->user()->id, 'created');

        return response()->json([
            'message' => 'Ticket created successfully',
            'ticket' => $ticket,
        ], 201);
    }


    public function getOneTicket(int $id){
        $ticket = $this->ticketService->getOneTicket($id);
        if ($ticket) {
            return response()->json([
                'message' => 'Ticket retrieved successfully',
                'ticket' => $ticket,
            ], 200);
        }
        return response()->json([
            'message' => 'Ticket not found',
        ], 404);
    }




    

    public function assign(Request $request, int $ticketId)
{
    $request->validate([
        'agent_id' => 'required|exists:users,id',
    ]);

    $assigned = $this->ticketService->assignToAgent($ticketId, $request->agent_id);

    if ($assigned) {

        $this->activityService->addActivity($ticketId, $request->user()->id, 'assingned');
        return response()->json([
            'message' => 'Ticket assigned to agent successfully',
        ]);
    }

    return response()->json([
        'message' => 'Ticket not found or assignment failed',
    ], 404);
}




    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        $updated = $this->ticketService->updateTicketStatus($id, $request->status);

        if ($updated) {

            $this->activityService->addActivity($ticket->id, $request->user()->id, 'status updated');
            return response()->json([
                'message' => 'Ticket status updated successfully',
            ]);
        }

        return response()->json([
            'message' => 'Ticket not found or update failed',
        ], 404);
    }


    public function updateProgress(Request $request, int $id)
    {
        $request->validate([
            'progress' => 'required|in:inprogress,done',
        ]);

        $updated = $this->ticketService->updateTicketProgress($id, $request->progress);

        if ($updated) {

            $this->activityService->addActivity($ticket->id, $request->user()->id, 'progress updated');

            return response()->json([
                'message' => 'Ticket progress updated successfully',
            ]);
        }

        return response()->json([
            'message' => 'Ticket not found or update failed',
        ], 404);
    }




    public function destroy(int $id)
    {
        $deleted = $this->ticketService->deleteTicket($id);

        if ($deleted) {
            $this->activityService->addActivity($ticket->id, $request->user()->id, 'deleted');
            return response()->json([
                'message' => 'Ticket deleted successfully',
            ]);
        }

        return response()->json([
            'message' => 'Ticket not found or deletion failed',
        ], 404);
    }

    


    public function getClientTickets(int $clientId)
    {
        $tickets = $this->ticketService->getClientTickets($clientId);

        return response()->json([
            'tickets' => $tickets,
        ]);
    }



    public function getAgentTickets(int $agentId)
    {
        $tickets = $this->ticketService->getAgentTickets($agentId);

        return response()->json([
            'tickets' => $tickets,
        ]);
    }

    


    public function getAllStatusOpen()
    {
        $tickets = $this->ticketService->getAllStatusOpen();

        return response()->json([
            'tickets' => $tickets,
        ]);
    }
}