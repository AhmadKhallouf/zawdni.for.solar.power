<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Load extends Model
{
    use HasFactory;

    protected $fillable = [
        'load',
        'watt',
        'description',
        'photo',
        
    ];

    public function carts(){
      return  $this->belongsToMany(Cart::class,'carts_loads_pivot','load_id','cart_id');
    }
}
