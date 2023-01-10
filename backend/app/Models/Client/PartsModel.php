<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsModel extends Model
{
    use HasFactory;

    protected $table = 'parts';

    protected $fillable = [
        'order_id',
        'description',
        'amount',
    ];

    public function orders()
    {
        return $this->hasOne(OrderModel::class, 'id');
    }
}
