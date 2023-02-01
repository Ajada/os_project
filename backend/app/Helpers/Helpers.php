<?php 

namespace App\Helpers;

use App\Helpers\TenantConnection;

class Helpers extends TenantConnection
{
    public static function setTenantConnection($tenant)
    {
        return self::reconnect($tenant);
    }

    public static function setTenant($model)
    {
        return $model->setTable(self::$host . $model->table);
    }
}