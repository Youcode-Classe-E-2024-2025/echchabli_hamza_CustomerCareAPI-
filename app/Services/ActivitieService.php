<?php

namespace App\Services;

use App\Models\Activitie;

class ActivitieService
{
    protected $activityModel;

    public function __construct(Activitie $activityModel)
    {
        $this->activityModel = $activityModel;
    }

   
    public function addActivity(int $ticketId, int $userId, string $action)
    {
        return $this->activityModel->create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'action' => $action,
        ]);
    }

    public function getTicketActivities(){

        // return $ticketID ;


        return $this->activityModel
        ->from('activities')
        ->join('tickets' , 'tickets.id' , '=' , 'activities.ticket_id')
        ->join('users', 'activities.user_id', '=', 'users.id')
        ->select([
            'activities.*',
            'users.name as user_name',
            'tickets.title as title' 
        ])
        ->get();
    }


}