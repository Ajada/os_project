<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;

class GetTenantId
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
        $request['tenant_id'] = $this->getTenantId($request->header('authorization'));

        return $next($request);
    }

    protected function getTenantId($req)
    {
        $header = explode('.' ,$req);

        return $header[0] ? 
            json_decode(base64_decode($header[1]))->{'sub'}->{'id'} : 
            response()->json(['error' => 'invalid token'], 403);
    }
}
