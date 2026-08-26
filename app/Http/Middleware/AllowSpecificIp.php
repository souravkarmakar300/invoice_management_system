<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowSpecificIp
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedIps = array_map(
            'trim',
            explode(',', env('ALLOWED_IPS', ''))
        );

        if (!in_array($request->ip(), $allowedIps, true)) {
            abort(403, 'Access Denied');
        }

        return $next($request);
    }
}
