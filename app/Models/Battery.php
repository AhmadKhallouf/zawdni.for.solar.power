<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'manufacture_company',
        'volt',
        'ampere',
        'model',
        'description',
        'price',
        'quantity_available',
        'photo',
    ];


}
