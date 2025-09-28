<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		    $host = $request->getHost();
    $parts = explode('.', $host);
    $subdomain = $parts[0] ?? null;

    if (!$subdomain) {
        return response()->json(['message' => 'Subdominio no detectado'], 400);
    }

    $tenant = \App\Models\Tenant::where('subdomain', $subdomain)->first();
    if (!$tenant) {
        return response()->json(['message' => 'Tenant no encontrado'], 404);
    }

    // configurar conexión dinámica
    \Illuminate\Support\Facades\Config::set(
        'database.connections.tenant',
        array_merge(
            config('database.connections.mysql'),
            ['database' => $tenant->database]
        )
    );

    \Illuminate\Support\Facades\DB::purge('tenant');
    \Illuminate\Support\Facades\DB::reconnect('tenant');

    // pasar el tenant al request
    $request->attributes->set('tenant', $tenant);
        return $next($request);
    }
}
