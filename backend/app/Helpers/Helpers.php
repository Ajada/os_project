<?php 

namespace App\Helpers;

use App\Helpers\TenantConnection;

class Helpers extends TenantConnection
{
    public static function setTenantConnection($tenant)
    {
        return self::reconnect($tenant);
    }

    /**
     * @param Illuminate\Database\Eloquent\Model $model
     * @return Illuminate\Database\Eloquent\Model
     */
    public static function setTenant($model)
    {
        return $model->setTable(self::$host . $model->table);
    }
}