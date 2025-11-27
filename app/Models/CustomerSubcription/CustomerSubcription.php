<?php

namespace App\Models\CustomerSubcription;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerSubcription extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'lname',
        'phone',
        'company_logo',
        'company_name',
        'webiste_Address',
        'Auth_key',
        'country',
        'city',
        'status',
        'page_titile',
        'slider_images',
        'page_content1',
        'page_content2',
        'page_content3',
        'page_content4',
        'hotel_Booking_Tag',
    ];
    
    public function hotelsProviders()
    {
        return $this->hasMany(HotelsProvider::class, 'customer_id');
    }

}
