<?php

namespace App\Models;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class booking_customers extends Authenticatable
{
     use HasApiTokens,Notifiable;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
     protected $table = 'booking_customers';
    protected $fillable = [
         'password',
    ];



}

