<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToursBooking extends Model
{
    use HasFactory;
    
    function cartDetails(){
        return $this->hasMany(Cart_details::class);
    }
}
