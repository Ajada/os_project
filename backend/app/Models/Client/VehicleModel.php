<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<<< HEAD:backend/app/Models/Client/VehicleModel.php
class VehicleModel extends Model
========
class VehiclesModel extends Model
>>>>>>>> feature_recreate_methods_to_controllers:backend/app/Models/Client/VehiclesModel.php
{
    use HasFactory;
    
    public $table = 'client_2.vehicles';

<<<<<<<< HEAD:backend/app/Models/Client/VehicleModel.php
    public $fillable = [
========
    public $table = '.vehicles';

    protected $fillable = [
>>>>>>>> feature_recreate_methods_to_controllers:backend/app/Models/Client/VehiclesModel.php
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
