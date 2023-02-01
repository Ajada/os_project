<?php

namespace App\Models\Manager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapAllSchemas extends Model
{
    use HasFactory;

    protected $table = 'information_schema.schemata';

}
