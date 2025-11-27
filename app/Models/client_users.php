<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class client_users extends Model
{
    use HasFactory;
    
    protected $fillables = [
        'password'
        ];
}
