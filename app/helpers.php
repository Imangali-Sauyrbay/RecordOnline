<?php

if (!function_exists('localizedRoute')) {
    /**
     * Generate a localized URL for the given named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  string|null  $locale
     * @return string
     *
     * @method string localizedRoute(string $name, array $parameters = [], string $locale = null)
     */
    function localizedRoute($name, $parameters = [], $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        $routePrefix = app('router')
        ->getRoutes()
        ->getByName($name);

        if($routePrefix) {
            $routePrefix = $routePrefix->getPrefix();
        } else {
            $routePrefix = '';
        }


        if (Str::contains($routePrefix, '{language?}')) {
            $parameters = array_merge(['language' => $locale], $parameters);
        }

        return route($name, $parameters);
    }
}

if (!function_exists('getEmailUrl')) {
    /**
     * Generate a localized URL for the given named route.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  string|null  $locale
     * @return string
     *
     * @method string localizedRoute(string $name, array $parameters = [], string $locale = null)
     */
    function getEmailUrl($subject, $body, $email)
    {
        $subject = urlencode($subject);
        $body = urlencode($body);
        $email = urlencode($email);

        if(\Browser::isDesktop()) {
            return "https://mail.google.com/mail/?view=cm&fs=1&to={$email}&su={$subject}&body={$body}";
        }

        return "mailto:{$email}?subject={$subject}&body={$body}";
    }
}
