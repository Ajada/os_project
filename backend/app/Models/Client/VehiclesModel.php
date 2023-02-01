<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiclesModel extends Model
{
    use HasFactory;

    public $table = '.vehicles';

    protected $fillable = [
        'brand',
        'model',
        'year',
        'plate',
        'fuel_type',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function order () 
    {
        return $this->belongsTo(OrderModel::class, 'user_id');
    }

}
