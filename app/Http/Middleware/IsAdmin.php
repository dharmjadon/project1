<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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

        if (Auth::user() && Auth::user()->user_type == 1) {
            return $next($request);
        }

        if (Auth::user() && Auth::user()->user_type == 3 || (Auth::user() && Auth::user()->user_type == 4)) {
            return redirect('/client/applied-job');
        }

        return redirect('/admin')->with('error','You have not admin access');

    }
}
