<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class companies extends Authenticatable
{
    use HasFactory;

    protected $table = 'companies'; // optional

    protected $fillable = [
        'email',
        'password',
        'name',
        'description',
        'package_code'
    ];

    protected $hidden = [
        'password',
    ];
}