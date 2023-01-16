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
        'vehicle_id',
        'mileage',
        'client_name',
        'cpf',
        'name_main_contact',
        'number_main_contact',
        'name_secondary_contact',
        'number_secondary_contact',
        'problem_related',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function automobile () 
    {
        return $this->hasMany(Automobile::class, 'id');
    }
}