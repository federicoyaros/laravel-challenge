<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class RequestLoggingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Obtenemos la información de la petición y respuesta
        $user = Auth::user();
        $service = $request->url();
        $requestBody = $request->all();
        $responseCode = $response->getStatusCode();
        $responseBody = $response->getContent();
        $originIp = $request->ip();

        // Guardamos la información en la base de datos
        DB::table('requests')->insert([
            'user_id' => $user ? $user->id : null,
            'service' => $service,
            'request_body' => json_encode($requestBody),
            'response_code' => $responseCode,
            'response_body' => Str::limit($responseBody, 1000000, '...'),
            'origin_ip' => $originIp,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $response;
    }
}
