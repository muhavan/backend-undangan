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
        
        // Daftar origin yang diizinkan
        $allowedOrigins = [
            'https://muhava.github.io',
            'http://127.0.0.1',
            'http://localhost'
        ];
        
        // Dapatkan origin dari request
        $origin = $request->server->get('HTTP_ORIGIN');
        
        // Periksa apakah origin ada dalam daftar yang diizinkan
        if ($origin && in_array($origin, $allowedOrigins)) {
            $header->set('Access-Control-Allow-Origin', $origin);
        } else {
            // Default ke domain utama jika origin tidak dikenali
            $header->set('Access-Control-Allow-Origin', 'https://muhava.github.io');
        }
        
        $header->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $header->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-CSRF-Token, X-Access-Key');
        $header->set('Access-Control-Allow-Credentials', 'true');
        $header->set('Access-Control-Expose-Headers', 'Authorization, Content-Type, Cache-Control, Content-Disposition');

        $vary = $header->has('Vary') ? explode(', ', $header->get('Vary')) : [];
        $vary = array_unique([...$vary, 'Accept', 'Origin', 'User-Agent', 'Access-Control-Request-Method', 'Access-Control-Request-Headers']);
        $header->set('Vary', join(', ', $vary));

        if ($request->method(Request::OPTIONS)) {
            return respond()->setCode(Respond::HTTP_NO_CONTENT);
        }

        return $next($request);
    }
}
