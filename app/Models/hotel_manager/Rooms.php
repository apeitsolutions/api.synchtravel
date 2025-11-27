<?php

namespace App\Models\hotel_manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;
    protected $fillables = [
            'room_view',
            'room_img',
            'price_type',
            'adult_price',
            'child_price',
            'quantity',
            'min_stay',
            'max_child',
            'max_adults',
            'extra_beds',
            'extra_beds_charges',
            'availible_from',
            'availible_to',
            'room_option_date',
            'price_week_type',
            'price_all_days',
            'weekdays',
            'weekdays_price',
            'weekends',
            'weekends_price',
            'room_description',
            'amenitites',
            'status',
            'hotel_id',
            'room_type_id',
            'room_type_name',
            'room_type_cat',
            'more_room_type_details',
            'additional_meal_type',
            'display_on_web',
            'markup_type',
            'markup_value',
            'price_all_days_wi_markup',
            'weekdays_price_wi_markup',
            'weekends_price_wi_markup',
            'additional_meal_type_charges',
    ];
}
