<?php

namespace App\Http\Middleware;
use App\User;
use App\Settings;
use Closure;
use Auth;use Illuminate\Support\Facades\App;
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if (!Auth::guest()) {
            $user = User::find(Auth::id());
            return $user->isAdmin(Settings::first()->admin_role) ? $next($request) : redirect('/');
        }

        return redirect('/login');
    }
}
