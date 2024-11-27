<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    use HasFactory;

    protected $fillable = [

        'type',
        'manufacture_company',
        'model',
        'watt',
        'width',
        'hight',
        'description',
        'price',
        'quantity_available',
        'photo',

    ];
} 
