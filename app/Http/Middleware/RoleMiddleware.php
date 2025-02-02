<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            // If the user doesn't have the required role, redirect them to a different page (e.g., home)
            return redirect('/');
        }

        return $next($request);
    }
}
