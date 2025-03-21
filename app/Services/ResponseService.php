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

    public function getResponsesByTicketId(int $ticketId)
    {
        return $this->response->where('ticket_id', $ticketId)->get();
    }


    public function getResponseFromId(int $FromId){

        return $this->response->where('from_id', $FromId)->get();

    }

    public function getResponseByToId(int $ToId){

        return $this->response->where('to_id', $ToId)->get();

    }
}
