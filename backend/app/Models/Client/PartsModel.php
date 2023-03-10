<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartsModel extends Model
{
    use HasFactory;

    public $table = '.parts';

    protected $fillable = [
        'order_id',
        'description',
        'amount',
    ];

    protected $hidden = [
        'order_id',
        'created_at',
        'updated_at',
    ];

    public function orders()
    {
        return $this->hasOne(OrderModel::class, 'id');
    }
}
