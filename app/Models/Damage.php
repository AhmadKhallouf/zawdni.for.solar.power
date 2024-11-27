<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_of_inverter',
        'manufacture_company',
        'model_of_inverter',
        'watt',
        'code',
        'description',
        'solution',
    ];
} 
