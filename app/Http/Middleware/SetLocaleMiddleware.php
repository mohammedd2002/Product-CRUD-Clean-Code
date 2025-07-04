<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $language = request()->segment(1);
        // dd($language);
        if ($language == 'ar' || $language == 'en') {
            App::setLocale($language);
            URL::defaults(['locale' => $language]);
            return $next($request);
        }
        abort('404');
    }
}
