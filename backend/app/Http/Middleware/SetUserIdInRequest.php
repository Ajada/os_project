<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;

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
        $parser = new Parser(new JoseEncoder);

        try {
            $token = $parser->parse(substr($request->header('authorization'), 7, strlen($request->header('authorization'))));
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // $this->getSchema($request->header('authorization'));

        return $next($request);
    }

    protected function getSchema($req)
    {
        $header = explode('.' ,$req);
        return $header[0] ? json_decode(base64_decode($header[1]))->{'sub'}->{'id'} : null;
    }
}
