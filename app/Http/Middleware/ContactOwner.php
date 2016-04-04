<?php

namespace App\Http\Middleware;

use App\Contact;
use Closure;
use Session;

class ContactOwner
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

            $contact = Contact::findOrFail($request->id);

            $contactOwner = $contact->user()->firstOrFail();

            if($contactOwner->id != $request->user()->id) {

                if($request->ajax()) {

                    return response()->json([
                        'error' => [
                            'message' => 'Request not authorized', 
                            'status' => '403'
                        ]
                    ], 403);

                }
                
                Session::Flash('error', 'Forbidden request');

                return redirect()->back();
                
            }

            return $next($request);

        } catch (\Exception $e) {

            if($request->ajax()) {
                return response()->json([
                        'error' => [
                            'message' => $e->getMessage(), 
                            'status' => '404'
                        ]
                    ], 404);
            }

            Session::Flash('error', $e->getMessage());

            return redirect()->back();
            

        }

    }
}
