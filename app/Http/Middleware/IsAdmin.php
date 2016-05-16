<?php

namespace App\Http\Middleware;

use Auth;
use Session;
use Closure;

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
        if(!Auth::user()->is_admin) {
            if($request->ajax() || $request->wantsJson()) {
                return response("Unauthorized request.", 401);
            } else {
                Session::flash("error", "Unauthorized request");
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
