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
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
    protected function authenticate($request, array $guards)
    {
        if(auth()->check() && !empty( auth()->user()->db_name )){

            $dynamic_db_name = auth()->user()->db_name;
            $config = \Config::get('database.connections.mysql');
            $config['database'] = $dynamic_db_name;
            $config['password'] = "";
            config()->set('database.connections.mysql', $config);
            \DB::purge('mysql');
        }

        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);
    }
}
