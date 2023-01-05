<?php

namespace App\Models;

use App\Models\Client\OrderModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automobile extends Model
{
    use HasFactory;

    protected $table = 'automobiles';

    protected $fillable = [
        'user_id',
        'car_model',
        'plate',
        'brand',
    ];

    public function order () 
    {
        return $this->belongsTo(OrderModel::class, 'user_id');
    }

}
