<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class flight_rute extends Model
{
    use HasFactory;

    protected $table = 'flight_rute';
    
    public function flight_seats_occupied(){
        return $this->hasMany(flight_seats_occupied::class, 'flight_route_id');
    }
}