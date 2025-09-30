<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ✅ Allow multiple roles separated by "|"
        $allowedRoles = explode('|', $roles);

        if (!in_array(Auth::user()->role, $allowedRoles)) {
            // Instead of abort(403), redirect based on role
            switch (Auth::user()->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'scheduler':
                    return redirect()->route('schedules.index');
                case 'faculty':
                    return redirect()->route('faculty.schedules.index');
                default:
                    return redirect()->route('dashboard'); // fallback
            }
        }

        return $next($request);
    }
}
