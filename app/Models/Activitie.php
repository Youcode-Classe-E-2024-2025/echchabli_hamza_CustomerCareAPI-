<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;


class Activitie extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_id', 'user_id', 'action'];

    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
