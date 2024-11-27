<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Load;
use App\Models\AdditionalLoad;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
        'status',
    ];


    //this table belong to user table in one to many relationship
    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }

    //this table belong to batteries table in many to many relationship
    public function batteries(){
        return $this->belongsToMany(Battery::class,'carts_batteries_pivot');
    }

    //this table belong to panels table in many to many relationship
    public function panels(){
        return $this->belongsToMany(Panel::class,'carts_panels_pivot');
    }

    //this table belong to inverters table in many to many relationship
    public function inverters(){
        return $this->belongsToMany(Inverter::class,'carts_inverters_pivot');
    }


    //this table belong to loads table in many to many relationship
     public function loads(){
        return $this->belongsToMany(Load::class,'carts_loads_pivot','cart_id','load_id');
    }

    //the cart table is relation with additional loads table in one to many relationship
    public function additional_loads(){
        return $this->hasMany(AdditionalLoad::class,'cart_id');
    }
}
