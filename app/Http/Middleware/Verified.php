<?php

namespace App\Http\Middleware;

use Closure;

class Verified
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
        if(is_null($request->user()->verified_at)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->back()->with("error", "Please verify your account to invite contacts");
            }
        }
        return $next($request);
    }
}
