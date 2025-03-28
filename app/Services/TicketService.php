<?php

namespace App\Services;

use App\Models\Ticket;

use App\Models\User;

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
            if ($ticket->save()) {
                return $ticket->refresh(); 
            }
        }
    
        return false;
    }
    

    public function getOneTicket(int $id)
    {
        return $this->ticketModel
        ->leftJoin('users as owners', 'tickets.owner_id', '=', 'owners.id')
        ->leftJoin('users as agents', 'tickets.agent_id', '=', 'agents.id')
        ->select([
            'tickets.*',
            'owners.name as owner_name',
            'agents.name as agent_name',
        ])
        ->where('tickets.id', $id)
        ->first();
    }
    

    public function getAllT(){
        $ticket = $this->ticketModel::All();
        return $ticket;
    }


    public function assignToAgent(int $id , int $agentId){


        $ticket = $this->ticketModel->find($id);
        $ticket->agent_id = $agentId;
        $ticket->save();
        $res= User::find($agentId);

        $this->updateTicketProgress($id , 'inprogress' );

        return [$res->name , $res->id];
    }
    
    public function updateTicketProgress(int $id, string $progress)
    {
        $ticket = $this->ticketModel->find($id);

        if ($ticket) {
            $ticket->progress = $progress;
            if ($ticket->save()) {
                return $ticket->refresh(); 
            }
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
        return $this->ticketModel
        ->where('tickets.owner_id', $clientId)
        ->leftJoin('users as agents', 'tickets.agent_id', '=', 'agents.id')
        ->select([
            'tickets.*',
            'agents.name as agent_name'
        ])
        ->get()
        ->toArray();
    }

    
    public function getAgentTickets(int $agentId)
    {
        return $this->ticketModel
    ->join('users', 'users.id', '=', 'tickets.owner_id')
    ->where('tickets.agent_id', $agentId)
    ->select('tickets.*', 'users.name as agent_name') 
    ->get()
    ->toArray();

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

    $query = $this->ticketModel->when($status && $status !== 'all', fn($q) => $q->where('status', $status))->orderBy('created_at', $sortDirection);

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