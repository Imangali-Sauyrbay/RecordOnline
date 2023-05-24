<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $language = $request->segment(1);
        $supported = config('app.supported_languages');

        if (!in_array($language, $supported)) {
            $locale = app()->getLocale();
            $accepts = $request->server('HTTP_ACCEPT_LANGUAGE');

            $userLocales = explode(';', $accepts);

            foreach ($userLocales as $userLocale) {
                $localeCode = explode(',', $userLocale);
                if(!array_key_exists(1, $localeCode))
                    continue;

                $localeCode = $localeCode[1];

                if(in_array($localeCode, $supported)) {
                    $locale = $localeCode;
                    break;
                }
            }

            $currentRouteName = Route::currentRouteName();

            if($currentRouteName) {
                return redirect()
                ->route($currentRouteName, ['language' => $locale]);
            }

            $route = explode('/', $request->path());

            if(count($route) > 1) {
                array_shift($route);
            }

            return redirect()->to($locale . '/' . implode('/', $route));
        }

        if(app()->getLocale() !== $language) {
            app()->setLocale($language);
        }

        return $next($request);
    }
}
