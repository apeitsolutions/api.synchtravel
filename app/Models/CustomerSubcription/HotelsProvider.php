<?php

namespace App\Models\CustomerSubcription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelsProvider extends Model
{
    use HasFactory;
    
    protected $table = 'customer_assign_hotel_providers';
    
    public function customerSubscription()
    {
        return $this->belongsTo(CustomerSubcription::class, 'customer_id');
    }
}
