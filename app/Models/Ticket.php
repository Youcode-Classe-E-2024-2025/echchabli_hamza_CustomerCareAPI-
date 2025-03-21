<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{  
    use HasFactory;

    protected $fillable = ['title', 'description', 'status', 'confirmed', 'owner_id', 'agent_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    
    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    
}
