<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SchedulerMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'scheduler') {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
