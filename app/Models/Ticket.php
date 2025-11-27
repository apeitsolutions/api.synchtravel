<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    
    
    public function conversation(){
        return $this->hasMany(Conversation::class,'ticket_id');
    }
    public function conversation_unread_message(){
    return $this->hasMany(Conversation::class, 'ticket_id')->where('read_message', 0);
    }
}
