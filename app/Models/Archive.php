<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_of_user',
        'type_of_system',
        'voltage_system',
        'number_of_inverters',
        'number_of_batteries',
        'number_of_panels',
        'distance_from_panels_to_inverter',
        'number_of_operating_hours_at_night',
        'total_day_capacity',
        'total_night_capacity',
        'run_way',
        'total_price',
    ];
}
 