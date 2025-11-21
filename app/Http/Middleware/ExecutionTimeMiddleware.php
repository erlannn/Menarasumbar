<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExecutionTimeMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);
        $duration = round($end - $start, 3);

        // Tambahkan header atau tampilkan di view/log
        $response->headers->set('X-Execution-Time', $duration . ' s');

        return $response;
    }
}
