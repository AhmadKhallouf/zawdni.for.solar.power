<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplementPrice extends Model
{
    use HasFactory; 

    protected $fillable = [
        'delivery_for_one_kiloMeter_cost',
        'base_panel_cost',
        'dollar_price_against_sp',
        'one_meter_of_cables_cost',
        'household_installation_cost',
        'agriculture_installation_cost',
        'industrial_installation_cost',
    ];
}
  