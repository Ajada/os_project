<?php

namespace App\Models;

use App\Models\Client\OrderModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        "order_id",
        "description",
        "status",
    ];

    public function orders () 
    {
        return $this->hasOne(OrderModel::class, 'id');
    }
}