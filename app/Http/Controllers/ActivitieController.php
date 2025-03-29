<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ActivitieService;



class ActivitieController extends Controller
{
    protected $ActivityService;

    public function __construct(ActivitieService  $ActivityService){

        $this->ActivityService=$ActivityService;

    }

     /**
     * @OA\Get(
     *     path="/api/activity",
     *     summary="Get activities for a specific ticket",
     *     description="Retrieve a list of activities related to a specific ticket by its ID.",
     *     operationId="getTicketActivities",
     *     tags={"Activities"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully retrieved activities for the ticket",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="res",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="ticket_id", type="integer", example=2),
     *                     @OA\Property(property="user_id", type="integer", example=3),
     *                     @OA\Property(property="action", type="string", example="created"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-28T14:27:59.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-28T14:27:59.000000Z"),
     *                     @OA\Property(property="user_name", type="string", example="Clayton Mcgee"),
     *                     @OA\Property(property="title", type="string", example="Aut dolores esse ist")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Ticket not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="ticket doesn't exist")
     *         )
     *     )
     * )
     */


    public function getTicktActiv(){

        $res = $this->ActivityService->getTicketActivities();

        if ($res) {
            return response()->json([
            
            'res' => $res ,

        ], 200);
        }
        

        return response()->json([
            
            'error' => "ticket doesn't exist" ,

        ], 400);

    }


}
