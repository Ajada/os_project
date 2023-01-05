<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    
    protected $table = 'orders';

    protected $fillable = [
        'main_contact',
        'secondary_contact',
        'plate',
        'car_model',
        'state',
        'problem_related',
        'problem_found',
    ];

    

}
