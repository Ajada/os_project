<?php

namespace App\Http\Middleware\Auth;

use App\Helpers\Helpers;
use Closure;
use Illuminate\Http\Request;

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
        $tenant = Helpers::setConnectionTenant($request['tenant_id']);

        try {
            $request['tenant_id'] = $tenant->host;
            return $next($request);
        } catch (\Throwable $th) { 
            if(json_decode($tenant->content())->error)
                return response()->json(['error' => json_decode($tenant->content())->error], 401);
        }
    }
}