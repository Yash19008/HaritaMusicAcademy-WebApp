<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Valid country slugs supported by this application.
     */
    private const SUPPORTED = ['in', 'us', 'uk', 'cad', 'uae'];

    /**
     * Resolve the {country} slug, load locale data, and share it with all views.
     * Returns 404 for unknown slugs.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $country = $request->route('country');

        if (!$country || !in_array($country, self::SUPPORTED, true)) {
            abort(404);
        }

        $locale = config("locales.{$country}");

        if (!$locale) {
            abort(404);
        }

        // Make available to all views rendered during this request
        View::share('locale', $locale);
        View::share('country', $country);

        return $next($request);
    }
}
