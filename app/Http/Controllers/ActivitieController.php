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


    public function getTicktActiv(){

        // return $T_id;
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
