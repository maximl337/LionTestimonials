<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use App\User;
use App\Http\Requests;
use App\ThirdPartyTestimonialSite;

class ExternalLinksController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}
    public function index(Request $request)
    {
    	$links = Auth::user()->thirdPartyTestimonialSites()->get();

    	return view('third_party_testimonial_sites.index', compact('links'));
    }

    public function create()
    {
    	return view('third_party_testimonial_sites.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    			'url' => 'required|url',
    			'provider' => 'required|max:255'
    		],[
                'url.url' => 'Invalid url. Dont forget to the the protocol, http://'
            ]);

    	$input = $request->input();

    	Auth::user()->thirdPartyTestimonialSites()->save(new ThirdPartyTestimonialSite([
    			'url' => $input['url'],
    			'provider' => $input['provider']
    		]));

    	Session::flash('success', 'Url created successfully.');

    	return redirect()->back();
    }

    public function edit($id)
    {

        try {
            
            $link = ThirdPartyTestimonialSite::findOrFail($id);

            return view('third_party_testimonial_sites.edit', compact('link'));

        } catch (\Exception $e) {
            
            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * [update description]
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
                'url' => 'required|url',
                'provider' => 'required|max:255'
            ],[
                'url.url' => 'Invalid url. Dont forget to the the protocol, http://'
            ]);

        $input = $request->input();

        try {
            
            $link = ThirdPartyTestimonialSite::findOrFail($id);

            $link->update([
                    'url' => $input['url'],
                    'provider' => $input['provider']
                ]);

            Session::flash('success', 'Link updated Successfully');

            return redirect()->back();

        } catch (\Exception $e) {
            
            Session::flash('error', $e->getMessage());

            return redirect()->back();
        }

    }

    public function destroy($id)
    {
        try {
            
            $link = ThirdPartyTestimonialSite::findOrFail($id);

            $link->delete();

            return response()->json([
                    "message" => "Deleted successfully"
                ], 200);

        } catch (\Exception $e) {
            
            return response()->json([
                    "message" => $e->getMessage()
                ], 500);
        }
    }
}
