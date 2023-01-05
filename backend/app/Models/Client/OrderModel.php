<?php

namespace App\Models\Client;

use App\Models\Automobile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    use HasFactory;
    
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'main_contact',
        'secondary_contact',
        'plate',
        'car_model',
        'state',
        'problem_related',
        'problem_found',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $primaryKey = 'user_id';

    public function automobile () 
    {
        return $this->hasMany(Automobile::class, 'user_id', 'user_id');
    }
}