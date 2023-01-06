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
        'user_id',
        'responsible',
        'external_parts',
        'service_description',
    ];

    public function orders () 
    {
        return $this->hasMany(OrderModel::class, 'id', 'id');
    }

}
