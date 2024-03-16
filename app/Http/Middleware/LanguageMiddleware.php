<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the selected language from the cookie or use the default locale
        $language = $request->cookie('preferred_language') ?? config('app.locale');

        // Set the current language
        app()->setLocale($language);

        return $next($request);
    }
}