<?php

namespace App\Http\Middleware;

use Closure;
use App\Testimonial;
use Auth;

class TestimonialOwner
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

        try {
            
            $testimonialOwner = Testimonial::findOrFail($request->get('id'))->user()->firstOrFail();

            if($testimonialOwner->id != $request->user()->id) {

                return response()->json([
                        'error' => [
                            'message' => 'Request not authorized', 
                            'status' => '403'
                        ]
                    ], 403);
                
            }

            return $next($request);

        } catch (\Exception $e) {

            return response()->json([
                        'error' => [
                            'message' => 'Request not authorized', 
                            'status' => '403'
                        ]
                    ], 403);

        }
        
    }
}
