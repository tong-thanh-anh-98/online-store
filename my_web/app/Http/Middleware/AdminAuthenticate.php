<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class AdminAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }

    /**
     * Authenticate the request for a given array of guards.
     *
     * This method is responsible for authenticating the request using the provided guards. If the 'admin' guard is checked
     * and the user is authenticated, it will return the 'admin' guard. Otherwise, it will call the 'unauthenticated'
     * method with the request and the 'admin' guard as parameters.
     *
     * @param  \Illuminate\Http\Request  $request The request being authenticated.
     * @param  array  $guards An array of guards to authenticate the request with.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|string|null The authenticated user or the guard name.
    */
    protected function authenticate($request, array $guards)
    {
        if ($this->auth->guard('admin')->check()) {
            return $this->auth->shouldUse('admin');
        }
        $this->unauthenticated($request, ['admin']);
    }
}