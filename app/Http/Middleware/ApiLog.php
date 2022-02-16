<?php

namespace App\Http\Middleware;

use Closure;

class ApiLog
{
    public function handle($request, Closure $next)
    {
        info('request api:' . $request->getUri());
        return $next($request);
    }

}
