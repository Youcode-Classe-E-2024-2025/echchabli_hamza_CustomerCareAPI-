<?php

namespace App\Services;

use App\Models\Ticket;


class TicketService
{
    protected $ticketModel;

    public function __construct(Ticket $ticketModel)
    {
        $this->ticketModel = $ticketModel;
    }

   
    public function createTicket(array $data)
    {
        return $this->ticketModel->create($data);
    }

   
    public function updateTicketStatus(int $id, string $status)
    {
        $ticket = $this->ticketModel->find($id);

        if ($ticket) {
            $ticket->status = $status;
            return $ticket->save();
        }

        return false;
    }

    public function getOneTicket(int $id){
        $ticket = $this->ticketModel->find($id);
        return $ticket;
    }

    public function getAllT(){
        $ticket = $this->ticketModel::All();
        return $ticket;
    }


    public function assignToAgent(int $id , int $agentId){


        $ticket = $this->ticketModel->find($id);
        $ticket->agent_id = $agentId;
        return $ticket->save();

    }
    
    public function updateTicketProgress(int $id, string $progress)
    {
        $ticket = $this->ticketModel->find($id);

        if ($ticket) {
            $ticket->progress = $progress;
            return $ticket->save();
        }

        return false;
    }

  
    public function deleteTicket(int $id)
    {
        $ticket = $this->ticketModel->find($id);

        if ($ticket) {
            return $ticket->delete();
        }

        return false;
    }

    public function getClientTickets(int $clientId)
    {
        return $this->ticketModel->where('owner_id', $clientId)->get()->toArray();
    }

    
    public function getAgentTickets(int $agentId)
    {
        return $this->ticketModel
    ->join('users', 'users.id', '=', 'tickets.owner_id')
    ->where('tickets.owner_id', $agentId)
    ->select('tickets.*', 'users.name as agent_name') 
    ->get()
    ->toArray();

    }

    public function getAllStatusOpen()
    {
        return $this->ticketModel->where('status', 'open')->get()->toArray();
    }



    // public function getPaginatedTickets($perPage = 3, $status = null, $sortDirection = 'desc')
    // {
    //     $query = $this->ticketModel->newQuery();
    
    //     if ($status && $status !== 'all') {
    //         $query->where('status', $status);
    //     }
    
    //     $sortDirection = strtolower($sortDirection);
    //     if (!in_array($sortDirection, ['asc', 'desc'])) {
    //         $sortDirection = 'desc';
    //     }
    
    //     // Always sort by created_at
    //     $query->orderBy('created_at', $sortDirection);
    
    //     return $query->paginate($perPage);
    // }

    public function getPaginatedTickets(int $perPage = 3, ?string $status = null, string $sortDirection = 'desc')
{
    $sortDirection = in_array(strtolower($sortDirection), ['asc', 'desc']) ? $sortDirection : 'desc';

    $query = $this->ticketModel
        ->when($status && $status !== 'all', fn($q) => $q->where('status', $status))
        ->orderBy('created_at', $sortDirection);

    $page = request()->input('page', 1);
    $offset = ($page - 1) * $perPage;

    $total =$query->count();

    return [
        'data' => $query->offset($offset)->limit($perPage)->get(),
        'current_page' => (int) $page,
        'per_page' => $perPage,
        'total' => $total ,
        'last_page'=> (int) ceil($total / $perPage),
    ];
}

    

}