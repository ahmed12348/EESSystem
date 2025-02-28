<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
class CheckUserType
{

    public function handle(Request $request, Closure $next, $type)
    {
        // Check if the user is authenticated and their type matches
        if (Auth::check() && Auth::user()->type === $type) {
            return $next($request);
        }

        // Redirect if the user does not have the right type
        return redirect()->route('home')->with('error', 'You do not have the required access.');
    }
}
