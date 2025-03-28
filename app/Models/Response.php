<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    
    use HasFactory;

    protected $fillable = ['response', 'ticket_id', 'user_id'];

    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    
    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}
