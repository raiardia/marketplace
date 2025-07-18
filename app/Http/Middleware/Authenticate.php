<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * The path to redirect to when the user is not authenticated.
     *
     * 
     */
    protected function redirectTo(Request $request): string
    {
        if (!$request->expectsJson()) {
            if (!$request->routeIs('admin')) {
                session()->flash('fail', 'You must be logged in to access this page.');
                return route('admin.login');
            }
        }

        return '';
    }

    
}
