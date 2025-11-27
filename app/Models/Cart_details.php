<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart_details extends Model
{
    use HasFactory;

    protected $table = 'cart_details';

    protected $fillables = [
        'tour_id',
        'generate_id',
        'tour_name',
        'adults',
        'childs',
        'sigle_price',
        'price',
        'sharing2',
        'sharing3',
        'sharing4',
        'sharingSelect',
        'image',
        'booking_id',
        'currency',
        'invoice_no',
    ];
    
    function Booking_data(){
        return $this->belongsTo(ToursBooking::class);
    }
}
