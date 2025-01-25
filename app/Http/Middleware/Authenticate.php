<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // if (! $request->expectsJson()) {
        //     return route('admin.login');
        // }
        
        if (!$request->expectsJson()) {
            // Check if the route belongs to vendor
            if ($request->is('vendor/*')) {
                return route('vendor.login'); // Redirect vendors to vendor login
            }
            
            // Default redirect for admin or other users
            return route('admin.login'); 
        }
     
        // if (! $request->expectsJson()) {
        //     return route('admin.login');
        // }
    }
    
}
