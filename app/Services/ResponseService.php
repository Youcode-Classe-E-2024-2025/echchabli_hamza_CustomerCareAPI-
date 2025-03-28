<?php

namespace App\Services;

use App\Models\Response;

class ResponseService
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function createResponse(array $responseData)
    {
        
        return $this->response->create($responseData);
    }

    // public function getResponsesByTicketId(int $ticketId)
    // {
    //     return $this->response->where('ticket_id', $ticketId)->get();
    // }


    public function getResponsesByTicketId(int $ticketId)
    {
        return $this->response
            ->where('ticket_id', $ticketId)
            ->join('users', 'responses.user_id', '=', 'users.id')
            ->select('responses.*', 'users.name as user_name')
            ->get();
    }
    

   
}
