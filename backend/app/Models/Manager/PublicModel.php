<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicModel extends Model
{
    use HasFactory;

    protected $table = 'public.hosts';

    protected $fillable = [
        'tenant_id',
        'host',
    ];

}
