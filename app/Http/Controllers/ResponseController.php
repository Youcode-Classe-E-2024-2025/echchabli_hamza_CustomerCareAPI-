<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use Illuminate\Http\Request;

use App\Http\Requests\ResponseStoreRequest;

class ResponseController extends Controller
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function store(ResponseStoreRequest $request)
    {

        $validated = $request->validated();


        $response = $this->responseService->createResponse($validated);

        return response()->json($response, 201);
    }

    public function getByTicketId($ticketId)
    {
        $responses = $this->responseService->getResponsesByTicketId($ticketId);

        return response()->json($responses);
    }

    public function getByFromId($fromId)
    {
        $responses = $this->responseService->getResponseFromId($fromId);

        return response()->json($responses);
    }

    public function getByToId($toId)
    {
        $responses = $this->responseService->getResponseByToId($toId);

        return response()->json($responses);
    }
}
