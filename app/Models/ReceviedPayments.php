<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceviedPayments extends Model
{
    use HasFactory;
    public function receivedFrom()
    {
        return $this->belongsTo(\App\Models\Accounts\CashAccounts::class,'received_from');
    }

    public function prepaidBy()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id');
    }

    
    
}
