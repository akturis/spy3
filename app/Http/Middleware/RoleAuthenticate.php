<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
class RoleAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next,  ...$roles)
    {

        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('welcome');
//                return redirect()->guest('login');
            }
        }

        $user = Auth::user();

        if ($user->hasrole('director')||$user->hasrole('admin')) return $next($request);
        $found = false;
        foreach($roles as $role) {
            if ($user->hasrole($role)) {
                $found = true;
                break;
            }
        }

        if (!$found) {
                return response('Access denied.', 401);
        }
        
        return $next($request);
    }
}