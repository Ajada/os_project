<?php 

namespace App\Helpers;

use App\Helpers\TenantConnection;


class Helpers extends TenantConnection
{
    public static function setConnectionTenant($tenant)
    {
        return self::reconnect($tenant);
    }
}