<?php

namespace App\Models\hotel_manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MetaInfo;
use App\Models\Policies;

class Hotels extends Model
{
    use HasFactory;
    protected $fillable = [
            'property_name',
            'property_img',
            'property_desc',
            'property_google_map',
            'latitude',
            'longitude',
            'property_country',
            'property_city',
            'price_type',
            'star_type',
            'property_type',
            'b2b_markup',
            'b2c_markup',
            'b2e_markup',
            'service_fee',
            'tax_type',
            'tax_value',
            'facilities',

            'hotel_email',
            'hotel_website',
            'property_phone',
            'property_address',
           
            'status',
            'owner_id',
    ];
    
     protected $table = 'hotels';
    
    public function metaInfo()
    {
        return $this->hasOne(MetaInfo::class, 'hotel_id');
    }
    
    public function policy()
    {
        return $this->hasOne(Policies::class, 'hotel_id');
    }
}
