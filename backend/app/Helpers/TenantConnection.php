<?php 

namespace App\Helpers;

use App\Models\Manager\PublicModel;
use Illuminate\Support\Facades\DB;

class TenantConnection
{
    protected static function reconnect($tenantConnection)
    {
        DB::purge('pgsql');
        
        $tenant = self::verifyHost($tenantConnection);

        if(is_null($tenant))
            return response()->json(['error' => 'tenant not found'], 401);

        config([
            'database.connections.pgsql.schema' => $tenant->host,
            'database.connections.pgsql.search_path' => $tenant->host
        ]);
        
        DB::connection('pgsql');

        return $tenant;
    }

    private static function verifyHost($host)
    {
        return PublicModel::where('tenant_id', $host)->first();
    }
}
