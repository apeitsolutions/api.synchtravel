<?php

namespace App\Models\CustomerSubcription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RoleManager extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     
    //   protected $table = [
    //     'synchron_adminpanel.role_managers',
    // ];
     
       protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_type',
        'user_permission',
        'customer_id',
        'is_active',
       
        
    ];
    
}
