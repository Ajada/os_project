<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicModel extends Model
{
    use HasFactory;

    public $table = "public.hosts";

    protected $fillable = [
        'client_id',
        'host',
    ];

}