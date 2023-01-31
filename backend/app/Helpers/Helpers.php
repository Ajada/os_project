<?php 

namespace App\Helpers;

use App\Helpers\TenantConnection;
use Illuminate\Database\Eloquent\Model;

class Helpers extends TenantConnection
{
    public static function setConnectionTenant($tenant)
    {
        return self::reconnect($tenant);
    }
    
    public static function setTenant($model)
    {
        return $model->setTable(self::$host . $model->table);
    }
}