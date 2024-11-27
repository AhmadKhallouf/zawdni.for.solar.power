<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inverter extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'model',
        'manufacture_company',
        'watt',
        'description',
        'price',
        'quantity_available',
        'photo',
    ];
}
