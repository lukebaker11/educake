<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ApiToken extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Token, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');

        $token = $request->header('APIToken');

        if($token !== 'sfJsXaf24m') {
            return response('Unauthorized Access', 401);
        }
        return $next($request);
    }
}
