<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseblesModel extends Model
{
    use HasFactory;

    protected $table = 'responsibles';

    protected $fillable = [
        'login_id',
        'name',
        'function',
    ];

}
