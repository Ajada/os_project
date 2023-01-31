<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;
    
    public $table = 'client_2.vehicles';

    public $fillable = [
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
    
    // public function setDefaultConenction($connect)
    // {
    //     $this->connection = $connect;
    // }

    public function order () 
    {
        return $this->belongsTo(OrderModel::class, 'user_id');
    }
}
