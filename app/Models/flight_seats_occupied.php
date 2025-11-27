<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class flight_seats_occupied extends Model
{
    use HasFactory;

    protected $table = 'flight_seats_occupied';
    
    public function flight_rute(){
        return $this->belongsTo(flight_rute::class, 'id');
    }
}