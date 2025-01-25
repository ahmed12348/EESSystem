<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorApproved
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'vendor' && Auth::user()->status !== 'Active') {
            Auth::logout();
            return redirect()->route('vendor.login')->with('error', 'Your account is not approved yet.');
        }

        return $next($request);
    }
}
