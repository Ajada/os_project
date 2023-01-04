<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automobile extends Model
{
    use HasFactory;

    protected $table = 'automobiles';

    protected $fillable = [
        'car_model',
        'plate',
        'brand',
    ];

}
