<?php

namespace App\Http\Middleware;

use Closure;

class Subscribed
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
        if ($request->user() && ! $request->user()->subscribed('primary') && ! $request->user()->isAdmin()) {
            // This user is not a paying customer...
            return redirect('billing');
        }

        return $next($request);
    }
}
