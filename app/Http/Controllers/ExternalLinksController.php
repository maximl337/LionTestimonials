<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Auth;
use Mail; 
use Session;
use App\User;
use App\Contact;
use App\Http\Requests;
use App\ExternalVendorReview;
use App\Contracts\GooglePlaces;
use App\Contracts\ExternalVendor;
use App\ThirdPartyTestimonialSite;
use App\Events\ExternalVendorStored;
use App\Transformers\YelpSearchTransformer;
use App\Transformers\GooglePlacesSearchTransformer;
use App\Transformers\GooglePlacesDetailsTransformer;

class ExternalLinksController extends Controller
{

    protected $externalVendor;

    protected $yelpSearchTransformer;

    protected $googlePlacesSearchTransformer;

    protected $googlePlacesDetailsTransformer;

	public function __construct(ExternalVendor $externalVendor, 
                                YelpSearchTransformer $yelpSearchTransformer, 
                                GooglePlacesSearchTransformer $googlePlacesSearchTransformer,
                                GooglePlacesDetailsTransformer $googlePlacesDetailsTransformer)
	{

        $this->externalVendor = $externalVendor;

        $this->yelpSearchTransformer = $yelpSearchTransformer;

        $this->googlePlacesSearchTransformer = $googlePlacesSearchTransformer;

        $this->googlePlacesDetailsTransformer = $googlePlacesDetailsTransformer;

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
    public function store(Request $request, GooglePlaces $googlePlaces)
    {

        try {

            $this->validate($request, [
                'business_url' => 'url',
                'business_name' => 'required',
                'business_id' => 'required',
                'provider' => 'required|max:255'
            ],[
                'url.url' => 'Invalid url. Dont forget to the the protocol, http://'
            ]);

            $user = Auth::user();

            $input = $request->only(['business_url', 'business_name', 'business_id', 'provider']);

            $input = array_filter($input, 'strlen');

            if(!in_array($input['provider'], [
                    'yelp', 
                    'google'
                ])) {

                throw new \Exception("Invalid provider given");
                
            }

            if($input['provider'] == 'yelp') {
                if(empty($input['business_url'])) {
                    throw new Exception("Business URL not found");
                }
            }

            // get business url
            if($input['provider'] == 'google') {

                // get url
                $details = $googlePlaces->getDetails($input['business_id']);

                if(empty($details['result']['url'])) {
                    throw new Exception("Could not retrieve business url");
                }

                $input['business_url'] = $details['result']['url'];


            }

            $thirdPartyTestimonialSite = new ThirdPartyTestimonialSite($input);

            $user->thirdPartyTestimonialSites()->save($thirdPartyTestimonialSite);

            if(!empty($details['result']['reviews'])) {
                
                // save google reviews since we have them
                foreach($details['result']['reviews'] as $review) {

                    // google does not have url for each review
                    // use the generic business url
                    $review['url'] = $thirdPartyTestimonialSite->business_url;

                    $review['external_review_site_id'] = $thirdPartyTestimonialSite->id;

                    $user->externalReviews()->save(new ExternalVendorReview($this->googlePlacesDetailsTransformer->transform($review)));

                } // end foreach

            } // check if google reviews exists

            event(new ExternalVendorStored($thirdPartyTestimonialSite));

            Session::flash('success', 'Url created successfully.');

            return redirect()->action('ExternalLinksController@index');

        } catch(\Illuminate\Database\QueryException $e) {

            if($e->getCode() == "23000") {

                $error = "This business already exists in the system";
            }

            Session::flash('error', $error);

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

    /**
     * [previewEmail description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function previewEmail(Request $request)
    {
        $links = Auth::user()->thirdPartyTestimonialSites()->get();

        $contacts = Auth::user()->contacts()->get();

        return view('third_party_testimonial_sites.email-preview', compact('links', 'contacts'));
    }

    /**
     * [sendEmail description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
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

    /**
     * [searchBusiness description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function searchBusiness(Request $request)
    {
        try {

            $this->validate($request, [
                    'provider' => 'required',
                    'location' => 'required',
                    'query_string' => 'required|min:3'
                ]);

            $input = $request->input();

            $results = $this->externalVendor->search($input['query_string'], $input['location'], $input['provider']);

            if($input['provider'] == 'yelp') {

                $response = $this->yelpSearchTransformer->transformCollection($results['businesses']);
            
            } elseif($input['provider'] == 'google') {

                //Log::info("Google", [$results]);

                $response = $this->googlePlacesSearchTransformer->transformCollection($results['results']);

            }

            return view('third_party_testimonial_sites.partials._search_results', [
                    'businesses' => $response
                ]);

            //return response()->json($response);
            
        } catch (Exception $e) {
            
            return response()->json([ 'error' => $e->getMessage() ]);
        }
    }

    /**
     * [FunctionName description]
     * @param string $value [description]
     */
    public function saveExternalReviewVendor(Request $request)
    {
        
        try {

            $this->validate($request, [
                    'provider' => 'required',
                    'url'   => 'required',

                ]);


            
        } catch (Exception $e) {
            
        }
    }
}
