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
        // Pastikan header dikirim sebelum konten apa pun
        if (headers_sent()) {
            error_log('Headers already sent before CORS middleware');
        }
        
        // Dapatkan objek header
        $header = respond()->Header();
        
        // Set header CORS secara eksplisit
        $header->set('Access-Control-Allow-Origin', '*');
        $header->set('Access-Control-Allow-Credentials', 'true');
        $header->set('Access-Control-Expose-Headers', 'Authorization, Content-Type, Cache-Control, Content-Disposition');
        
        // Pastikan header Vary diatur dengan benar
        $vary = $header->has('Vary') ? explode(', ', $header->get('Vary')) : [];
        $vary = array_unique([...$vary, 'Accept', 'Origin', 'User-Agent', 'Access-Control-Request-Method', 'Access-Control-Request-Headers']);
        $header->set('Vary', join(', ', $vary));
        
        // Tangani permintaan preflight OPTIONS
        if ($request->method() === 'OPTIONS') {
            $header->unset('Content-Type');
            
            // Set header untuk permintaan preflight
            $header->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $header->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Requested-With, X-CSRF-TOKEN');
            $header->set('Access-Control-Max-Age', '86400'); // Cache preflight selama 24 jam
            
            // Kirim respons 204 No Content untuk permintaan preflight
            http_response_code(204);
            exit; // Penting: hentikan eksekusi untuk permintaan OPTIONS
        }
        
        // Lanjutkan ke middleware berikutnya atau handler untuk permintaan non-OPTIONS
        return $next($request);
    }
}
