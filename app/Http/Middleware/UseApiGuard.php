<?php
namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Auth;


class UseApiGuard
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Auth::shouldUse('api');

        return $next($request);
    }
}