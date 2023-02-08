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
        $tenant = Helpers::setTenantConnection($request['tenant_id']);

        try {
            if(json_decode($tenant->content())->{'error'})
                return response()->json(['error' => 'tenant not found'], 401);
        } catch (\Throwable $th) {
            $request->request->remove('tenant_id');
        }

        return !is_null($tenant) ?
            $next($request) : 
                response()->json(['error' => 'tenant not found'], 401);
    }
}
