<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permission): Response
    {
        if (Auth::guard()->guest()) {
            return redirect()->route('login')->with('auth_alert', 'You have to log in first to access this page!');
        }


        if (Auth::check()) {
            $userRole = Auth::user()->role->type;

            if (in_array($userRole, $permission)) {
                return $next($request);
            }

            abort(403);
        }

        return redirect()->route('login');
    }
}