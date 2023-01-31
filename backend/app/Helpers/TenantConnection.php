<?php 

namespace App\Helpers;

use App\Models\Manager\PublicModel;
use Illuminate\Support\Facades\DB;

class TenantConnection
{
    protected static string $host;

    protected static function reconnect($tenantConnection)
    {
        DB::purge('pgsql');
        
        $tenant = self::verifyHost($tenantConnection);

        if(is_null($tenant))
            die(json_encode(['error' => 'tenant not found']));

        config([
            'database.connections.pgsql.schema' => $tenant->host,
            'database.connections.pgsql.search_path' => $tenant->host
        ]);
        
        DB::connection('pgsql');

        self::$host = $tenant->host;

        return $tenant;
    }

    private static function verifyHost($host)
    {
        return PublicModel::where('tenant_id', $host)->first();
    }

}
