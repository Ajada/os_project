<?php

namespace App\Http\Middleware\Auth;

use App\Models\Manager\PublicModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetTenantId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = PublicModel::where('tenant_id', $request['tenant_id'])->first();

        if($tenant) 
        {
            $request['tenant_id'] = $tenant->host;

            DB::purge('pgsql');

            config([
                'database.connections.pgsql.schema' => $request['tenant_id'],
                'database.connections.pgsql.search_path' => $request['tenant_id']
            ]);

            DB::connection('pgsql');

            dd(config('database.connections.pgsql'));

            return $next($request);
        }

        return response()->json([
            'error' => 'tenant not found'
        ], 401);

    }
}
