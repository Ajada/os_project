<?php 

namespace App\Helpers;

use App\Helpers\TenantConnection;

class Helpers extends TenantConnection
{
    public static function setTenantConnection($tenant)
    {
        return self::reconnect($tenant);
    }

    public function setTenant()
    {
        return ;
    }

}