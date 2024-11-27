<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class AdditionalLoad extends Model
{
    use HasFactory;

    protected $table = 'additional_loads';

    protected $fillable = [
        'cart_id',
        'load',
        'watt',
        'run_at_night',
        'operating_voltage',
    ];


    //this table belong to cart table in one to many relationship
    public function carts(){
        return $this->belongsTo(Cart::class,'id');
    }
}
