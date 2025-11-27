<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class cash_accounts_remarks extends Authenticatable
{
    use HasFactory;

    protected $table = 'cash_accounts_remarks';
}