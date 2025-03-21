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
        return $this->ticketModel->where('agent_id', $agentId)->get()->toArray();
    }

    public function getAllStatusOpen()
    {
        return $this->ticketModel->where('status', 'open')->get()->toArray();
    }
}