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

   
    public function logActivity(int $ticketId, int $userId, string $action)
    {
        return $this->activityModel->create([
            'ticket_id' => $ticketId,
            'user_id' => $userId,
            'action' => $action,
        ]);
    }
}