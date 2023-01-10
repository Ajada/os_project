<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;

class SetUserIdInRequest
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
        // if(is_null($request['user_id']))
        //     $request['user_id'] = $this->getJWT($request->header('Authorization'));

        return $next($request);
    }

    protected function getJWT($req)
    {
        $header = explode('.' ,$req);
        return $header[0] ? json_decode(base64_decode($header[1]))->{'sub'}->{'id'} : null;
    }
}
