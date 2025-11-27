<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking_users extends Model
{
    use HasFactory;
    protected $fillables = [
        'fname',
        'lname',
        'email',
        'phone_no',
        'passport_no',
        'passport_expire',
        'address',
        'country',
    ];
}
