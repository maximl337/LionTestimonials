<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Mail; 
use App\Contact;
use Session;
use App\User;
use App\Http\Requests;
use App\ThirdPartyTestimonialSite;

class ExternalLinksController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');

        $this->middleware('subscribed');
	}

    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
    	$links = Auth::user()->thirdPartyTestimonialSites()->get();

    	return view('third_party_testimonial_sites.index', compact('links'));
    }


    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
    	return view('third_party_testimonial_sites.create');
    }
    
    /**
     * [create description]
     * @return [type] [description]
     */
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
    
    /**
     * [create description]
     * @return [type] [description]
     */
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
    
    /**
     * [create description]
     * @return [type] [description]
     */
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

    public function previewEmail(Request $request)
    {
        $links = Auth::user()->thirdPartyTestimonialSites()->get();

        $contacts = Auth::user()->contacts()->get();

        return view('third_party_testimonial_sites.email-preview', compact('links', 'contacts'));
    }

    public function sendEmail(Request $request)
    {
        
        $this->validate($request, [
                    'contact_id' => 'required|exists:contacts,id',
                    'links' => 'required',
                    'message' => 'required'
                ]);

        try {
            
            $input = $request->input();

            $contact = Contact::findOrFail($input['contact_id']);

            $user = Auth::user();

            $message = $input['message'];

            $url = [];

            // add links to message:
            foreach($input['links'] as $linkId) {

                $site = ThirdPartyTestimonialSite::findOrFail($linkId);

                $url[] = $site->url;
            }

            $data = [
                "msg" => $message,
                "urls" => $url
            ];

            // send mail
            Mail::send('emails.inviteExternal', $data, function($m) use ($contact) {
                $m->from('robot@sellwithreviews.com', Auth::user()->getName());
                $m->to($contact->email, $contact->first_name)->subject('Testimonial Request');
            });

            return redirect()->back()->with('success', 'Emailed successfully');

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }
    }
}
