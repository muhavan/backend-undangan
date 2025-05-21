<?php

namespace App\Middleware;

use Closure;
use Core\Http\Request;
use Core\Http\Respond;
use Core\Middleware\MiddlewareInterface;

final class CorsMiddleware implements MiddlewareInterface
{
    public function handle(Request $request, Closure $next)
    {
        $header = respond()->getHeader();

        $header->set('Access-Control-Allow-Origin', '*');
        $header->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $header->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization');
        $header->set('Access-Control-Allow-Credentials', 'true');

        // Untuk permintaan preflight (OPTIONS), langsung respons 204
        if ($request->method() === 'OPTIONS') {
            return respond('', 204);
        }

        return $next($request);
    }
}
