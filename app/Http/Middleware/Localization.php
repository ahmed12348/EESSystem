<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
{
    public function handle($request, Closure $next)
    {
        // Check if a locale exists in the session
        $locale = Session::get('locale', config('app.locale'));

        // Set the application locale
        App::setLocale($locale);
    //   dd($request);
        return $next($request);
    }
}
