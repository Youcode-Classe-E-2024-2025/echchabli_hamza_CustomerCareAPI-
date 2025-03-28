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


    /**
     * @OA\Post(
     *     path="/api/tickets",
     *     summary="Create a new ticket",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "owner_id"},
     *             @OA\Property(property="title", type="string", example="Sample Ticket Title"),
     *             @OA\Property(property="description", type="string", example="Sample Ticket Description"),
     *             @OA\Property(property="owner_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Ticket created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket created successfully"),
     *             @OA\Property(property="ticket", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    
     public function store(ticketStoreRequest $request)
     {
         try {
            
             $data = $request->validated();
     
             $ticket = $this->ticketService->createTicket($data);
              

             $this->activityService->addActivity($ticket->id, $data['owner_id'], 'created');
     
             
             return response()->json([
                 'message' => ' created successfully',
                 'ticket' => $ticket,
             ], 201);
         } catch (\Exception $e) {
            
             \Log::error('Error creating ticket: ' . $e->getMessage(), [
                 'exception' => $e
             ]);
     
             
             return response()->json([
                 'message' => '',
                 'error' => $e->getMessage()
             ], 500);
         }
     }
     

     /**
     * @OA\Get(
     *     path="/api/tickets/{id}",
     *     summary="Get a ticket by ID",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Ticket retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket retrieved successfully"),
     *             @OA\Property(property="ticket", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Ticket not found")
     * )
     */
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



 /**
     * @OA\Post(
     *     path="/api/tickets/{ticketId}/assign",
     *     summary="Assign ticket to an agent",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="ticketId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"agent_id"},
     *             @OA\Property(property="agent_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Ticket assigned successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket assigned to agent successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Ticket not found or assignment failed")
     * )
     */
    

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
            'name' => $assigned
        ]);
    }

    return response()->json([
        'message' => 'Ticket not found or assignment failed',
    ], 404);
}


    /**
     * @OA\Put(
     *     path="/api/tickets/{id}/status",
     *     summary="Update ticket status",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="open")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Ticket status updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket status updated successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Ticket not found or update failed")
     * )
     */


    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:open,closed',
        ]);

        $updated = $this->ticketService->updateTicketStatus($id, $request->status);

        if ($updated) {

            $this->activityService->addActivity($updated->id, $updated->owner_id, 'status updated');
            return response()->json([
                'message' => 'Ticket status updated successfully',
                'mlm' => $updated
            ]);
        }

        return response()->json([
            'message' => 'Ticket not found or update failed',
        ], 404);
    }


      /**
     * @OA\Post(
     *     path="/api/tickets/{id}/progress",
     *     summary="Update ticket progress",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"progress"},
     *             @OA\Property(property="progress", type="string", example="inprogress")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Ticket progress updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket progress updated successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Ticket not found or update failed")
     * )
     */
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



    /**
     * @OA\Delete(
     *     path="/api/tickets/{id}",
     *     summary="Delete a ticket",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Ticket deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ticket deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Ticket not found or deletion failed")
     * )
     */



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

    
     /**
     * @OA\Get(
     *     path="/api/tickets/client/{clientId}",
     *     summary="Get all tickets for a client",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Tickets retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tickets", type="array", items=@OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tickets not found")
     * )
     */

     public function getClientTickets(int $clientId)
     {
         \Log::info('Authenticated User:', ['user' => $clientId]);
     
         $tickets = $this->ticketService->getClientTickets($clientId);
         return response()->json(['tickets' => $tickets]);
     }
     



    /**
     * @OA\Get(
     *     path="/api/tickets/agent/{agentId}",
     *     summary="Get all tickets for an agent",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="agentId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Tickets retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tickets", type="array", items=@OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tickets not found")
     * )
     */

    public function getAgentTickets(int $agentId)
    {
        $tickets = $this->ticketService->getAgentTickets($agentId);

        return response()->json([
            'tickets' => $tickets,
        ]);
    }

    


     /**
     * @OA\Get(
     *     path="/api/tickets/open",
     *     summary="Get all tickets with status open",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Tickets retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tickets", type="array", items=@OA\Items(type="object"))
     *         )
     *     )
     * )
     */


    public function getAllStatusOpen()
    {
        $tickets = $this->ticketService->getAllStatusOpen();

        return response()->json([
            'tickets' => $tickets,
        ]);
    }



     /**
     * @OA\Get(
     *     path="/api/tickets",
     *     summary="Get all tickets ",
     *     tags={"Tickets"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(response=200, description="Tickets retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tickets", type="array", items=@OA\Items(type="object"))
     *         )
     *     )
     * )
     */

     public function getAllTickets(Request $request)
{
    $perPage = $request->input('per_page', 3);
    $status = $request->input('status', null);
    $sortDirection = $request->input('sort_direction', 'desc'); // 'asc' or 'desc'

    $tickets = $this->ticketService->getPaginatedTickets($perPage, $status, $sortDirection);

    return response()->json([
        'tickets' => $tickets,
    ]);
}





}