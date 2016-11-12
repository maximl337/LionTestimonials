<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use App\User;
use App\Branding;
use App\Http\Requests;

class BrandingController extends Controller
{
    
    public function __construct()
    {
    	$this->middleware('auth');

        $this->middleware('subscribed');
    }

    public function edit()
    {
    	$branding = Auth::user()->branding()->first();

        if(!$branding) {
            $branding = new Branding;
        }

    	return view('branding.edit', compact('branding'));
    }

    /**
     * [update description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update(Request $request)
    {
    	try {
    		
            $this->validate($request, [
                    'primary_color' => 'required',
                    'background_color' => 'required',
                    'text_color'    => 'required'
                ]);

            $input = $request->only(['primary_color', 'background_color', 'text_color']    );

            // remove all other branding stuff
            Auth::user()->branding()->delete();

            $branding = new Branding($input);

            Auth::user()->branding()->save($branding);

            Session::flash('success', "Branding Updated");

            return redirect()->back()->with(compact('branding'));

    	} catch (Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

    		
    	}
    }
}
