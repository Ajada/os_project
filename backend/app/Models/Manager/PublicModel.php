<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicModel extends Model
{
    use HasFactory;

<<<<<<< HEAD
    public $table = "public.hosts";
=======
    protected $table = 'public.hosts';
>>>>>>> feature_recreate_methods_to_controllers

    protected $fillable = [
        'client_id',
        'host',
    ];

}
