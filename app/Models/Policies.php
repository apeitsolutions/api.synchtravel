<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\hotel_manager\Hotels;

class Policies extends Model
{
    use HasFactory;
    protected $fillables = [
        'check_in_form',
        'check_out_to',
        'payment_option',
        'policy_and_terms'
    ];
    
    protected $table = 'policies';
    
    public function hotels()
    {
        return $this->belongsTo(Hotels::class, 'id');
    }
}
