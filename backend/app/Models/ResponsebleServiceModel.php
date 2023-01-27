<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponsebleServiceModel extends Model
{
    use HasFactory;

    protected $table = 'responsible_services';

    protected $fillable = [
        'service_id',
        'responsible_id',
    ];

}
