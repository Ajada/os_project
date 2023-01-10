<?php

namespace App\Models;

use App\Models\Client\OrderModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Automobile extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $fillable = [
        'brand',
        'model',
        'year',
        'plate',
        'fuel_type',
    ];

    public function order () 
    {
        return $this->belongsTo(OrderModel::class, 'user_id');
    }

}
