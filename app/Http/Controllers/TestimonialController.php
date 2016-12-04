<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Auth;
use View;
use Mail;
use Storage;
use Session;
use App\Branding;
use App\User;
use App\Contact;
use Carbon\Carbon;
use App\Invitation;
use App\Testimonial;
use App\Http\Requests;
use App\Contracts\TestimonialInterface;
use App\Http\Requests\StoreTestimonialRequest;

class TestimonialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'store', 'storeFromDesktop', 'storeFromPhone', 'publicTestimonials', 'thankyou', 'showTestimonialVideo', 'getTestimonial']]);

        $this->middleware('testimonial.owner', ['only' => ['approve', 'destroy']]);

        $this->middleware('subscribed', ['only' => ['getTestimonials', 'getTestimonial', 'approve', 'create', 'store']]);

    }

    /**
     * [getTestimonials description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getTestimonials(Request $request, TestimonialInterface $testimonialService)
    {
        $limit = $request->get('limit') ?: 9;

        $page = $request->get('page') ?: 0;

        $filter = $request->get('filter') ?: 'all';

        $user = Auth::user();

        if($filter == 'approved') {

            $testimonials = $user->testimonials()->approved()->with('contact')->paginate($limit);

        } elseif ($filter == 'unapproved') {

            $testimonials = $user->testimonials()->unapproved()->with('contact')->paginate($limit);

        } else {

            $testimonials = $user->testimonials()->with('contact')->paginate($limit);
        }

        // average rating
        $average_rating = $testimonialService->getAverageRating($user);

        $testimonials_by_providers = $testimonialService->getReviewCountByProvider($user);

        return view('testimonials.index', compact('testimonials', 'average_rating', 'testimonials_by_providers'));
    }

    /**
     * [getExternalTestimonials description]
     * @param  Request              $request            [description]
     * @param  TestimonialInterface $testimonialService [description]
     * @return [type]                                   [description]
     */
    public function getExternalTestimonials(Request $request, TestimonialInterface $testimonialService)
    {
        try {

            $limit = $request->get('limit') ?: 2;

            $page = $request->get('page') ?: 0;

            $filter = $request->get('filter') ?: 'all';

            $user = Auth::user();

            $testimonials = $user->externalReviews()->with('vendor')->paginate($limit);

            // average rating
            $average_rating = $testimonialService->getAverageRating($user);

            $testimonials_by_providers = $testimonialService->getReviewCountByProvider($user);

            return view('testimonials.external_index', compact('testimonials', 'average_rating', 'testimonials_by_providers'));
            
        } catch (Exception $e) {
            
        }
    }

    /**
     * [publicTestimonials description]
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function publicTestimonials($id, Request $request)
    {
        $limit = $request->get('limit') ?: 1;

        $page = $request->get('page') ?: 0;

        try {

            $testimonials = User::findOrFail($id)->testimonials()->approved()->with('contact')->paginate($limit);

            if ($request->ajax()) {
                return response()->json(View::make('testimonials._partials.testimonials', array('testimonials' => $testimonials))->render());
            }

            return view('testimonials.public', compact('testimonials'));

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return view('errors.404');

        } catch (\Exception $e) {

            return view('errors.503', ['message' => $e->getMessage()]);
        }
        
    }

    /**
     * [getTestimonial description]
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getTestimonial($id, Request $request)
    {
        try {

            $testimonial = Testimonial::findOrFail($id);

            // if unapproved and not owner
            if(is_null($testimonial->approved_at)) {

                if(!Auth::check()) {
                    return redirect()->back()->with("error", "Forbidden access");    
                }
                
                if($testimonial->user()->first()->id != Auth::id()) {
                    return redirect()->back()->with("error", "Forbidden access");       
                } 
                
            } 

            $user = $testimonial->user()->first();

            return view('testimonials.show', compact('testimonial', 'user'));

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Could not find the resource');

            return redirect()->back();

        } catch(\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();
        }
    }

	/**
	 * [create description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
    public function create(Request $request)
    {
    	
    	$this->validate($request, [
    			'id' => 'required|exists:contacts,id',
                'token' => 'required|exists:invitations,token',
    		], [
    			'token.exists' => "The given token was not found in our records",
    			'id.exists' => "The given id was not found in our records",
    			'id.required' => "ID not found to create a testimonial",
    			'token.required' => "Token not found"
    		]);

    	$input = $request->input();

    	try {

    		$invitation = Invitation::where('contact_id', $input['id'])->where('token', $input['token'])->firstOrFail();

            $contact = $invitation->contact()->first();

    		$user = $contact->user()->first();

            $branding = $user->branding()->first();

            if(!$branding) {
                $branding = new Branding();
            }

    		// check if token matches
    		if($invitation->token != $input['token']) {
    			throw new \Exception("Given token does not match the one on record");
    		}

    		$data = [
    			'contact' => $contact,
    			'user' => $user,
                'branding' => $branding,
                'invitation' => $invitation
    		];

    		return view('testimonials.create', compact('data'));

    	} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact with the given id does not exist');

            return view('testimonials.create');

        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return view('testimonials.create');

        }

    }

    /**
     * Store testimonial
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(StoreTestimonialRequest $request)
    {

        try {

            $input = $request->only(['contact_id', 'rating', 'body', 'token', 'thumbnail', 'url', 'email', 'invite_token']);

            $input = array_filter($input, 'strlen');

            $exists = Testimonial::where('user_id', $request->get('user_id'))
                                    ->where('contact_id', $input['contact_id'])
                                    ->where('invite_token', $input['invite_token'])
                                    ->exists();

            if($exists) {
                throw new \Exception("Token to submit testimonial is exhausted");
            }

            // create testimonial
            $testimonial = new Testimonial($input);

            $user = User::findOrFail($request->get('user_id'));

            $user->testimonials()->save($testimonial);

            $data = [
                'user' => $user,
                'testimonial' => $testimonial
            ];

            // mail the user
            Mail::send('emails.new_testimonial', $data, function($m) use ($user) {

                $m->to($user->email, $user->getName())->subject("New Testimonial");
            });

            // get testimonial thanks vid

            // get third part sites
            $video = $user->videos()->where('thanks_video', true)->first();

            $external_sites = $user->thirdPartyTestimonialSites()->get();

            $branding = $user->branding()->first();

            return view('testimonials.thankyou', compact('video', 'external_sites', 'branding'));

        } catch (\Exception $e) {

            return redirect()->back()->with("error", $e->getMessage());

        }

    }

    /**
     * [approve description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function approve(Request $request)
    {
        $this->validate($request, [
                'id' => 'required|exists:testimonials,id',
            ]);

        try {

            $testimonial = Testimonial::findOrFail($request->get('id'));

            $testimonial->update(['approved_at' => Carbon::now()]);

            $contact = $testimonial->contact()->first();

            $url = env('APP_URL') . 'users/' . $testimonial->user_id . '/public';

            $data = [
                'contact' => $contact,
                'testimonial' => $testimonial,
                'url' => $url
            ];

            Mail::send('emails.testimonial_approved', $data, function($m) use ($testimonial, $contact) {

                $m->to($testimonial->email, $contact->first_name)->subject("Testimonial approved");
            });


            return response()->json([
                'message' => 'Testimonial updated'
            ], 200);

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Could not find the testimonial'
            ], 404);

        } catch(\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
        
    }

    /**
     * [storeFromPhone description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeFromPhone(Request $request)
    {

        Log::info('Testimonial:storeFromPhone', ['data' => $request->input()]);

        $this->validate($request, [

                'contact_id' => 'required|exists:contacts,id',
                'user_id' => 'required|exists:contacts,user_id|exists:users,id',
                'rating' => 'required|integer|max:5|min:1',
                'email' => 'email|required',
                'video' => 'max:20000|required_without:body',
                'body' => 'required_without:video',
                //'g-recaptcha-response' => 'required|recaptcha',

            ], [
                'video.required_without' => 'Please add a video if not adding any text',
                'video.max' => 'Video is larger than 20MB. Please upload a smaller video',
                'body.required_without' => 'Please add some text if not adding a video',
                'email.email' => 'Email is not valid',
                'email.required' => 'Email is required'
            ]);

        $input = $request->input();

        $exists = Testimonial::where('contact_id', $input['contact_id'])->where('user_id', $input['user_id'])->exists();

        // if($exists) {

        //     Session::flash('error', 'You have already added a testimonial');

        //     return redirect()->back();
        // }

        if($request->hasFile('video')) {
            
            //$storage = storage_path('media');
            
            $file = $request->file('video');

            $type = $file->getMimeType();

            $fileName = $input['contact_id'] . '-' . time() . '-' . md5($file->getClientOriginalName()) . $file->getClientOriginalExtension();

            $storage_path = 'sellwithreviews.com/'.env('APP_ENV').'/videos/' . $input['user_id'] . '/' . $fileName;

            Storage::put(
                        $storage_path,
                        file_get_contents($file->getRealPath())
                    );

            //$file->move($storage, $fileName);
        }
        
        // create testimonial
        $testimonial = new Testimonial([

                'contact_id' => $input['contact_id'],
                'rating' => $input['rating'],
                'body' => $input['body'],
                'video' => !empty($fileName) ? $fileName : "",
                'video_type' => !empty($type) ? $type : "",
                'storage_path' => !empty($storage_path) ? $storage_path : "",
                'email' => $input['email']

            ]);

        $user = User::findOrFail($input['user_id']);

        $user->testimonials()->save($testimonial);

        $data = [
            'user' => $user,
            'testimonial' => $testimonial
        ];

        // mail the user
        Mail::send('emails.new_testimonial', $data, function($m) use ($user) {

            $m->to($user->email, $user->getName())->subject("New Testimonial");
        });


        Session::flash('success', 'Testimonial Created. Thank you.');

        return redirect()->back();


    }

    public function storeFromDesktop(Request $request)
    {
        $this->validate($request, [

                'contact_id' => 'required|exists:contacts,id',
                'user_id' => 'required|exists:contacts,user_id|exists:users,id',
                'rating' => 'required|integer|max:5|min:1',
                'email' => 'email|required',
                'video' => 'max:20000|required_without:body',
                'body' => 'required_without:video',
                //'g-recaptcha-response' => 'required|recaptcha',

            ], [
                'video.required_without' => 'Please add a video if not adding any text',
                'body.required_without' => 'Please add some text if not adding a video',
                'email.email' => 'Email is not valid',
                'email.required' => 'Email is required'
            ]);

        try {

            $input = $request->input();

            $exists = Testimonial::where('contact_id', $input['contact_id'])->where('user_id', $input['user_id'])->exists();

            // if($exists) {

            //     Session::flash('error', 'You have already added a testimonial');

            //     return response()->json([
            //         'error' => "You have already added a testimonial"
            //     ], 409);
            // }

            if($request->hasFile('video')) {
            
                //$storage = storage_path('media');
                
                $file = $request->file('video');

                $type = 'video/webm';

                $fileName = $input['contact_id'] . '-' . time() . '-' . md5($file->getClientOriginalName()) . $file->getClientOriginalExtension();

                $storage_path = 'sellwithreviews.com/'.env('APP_ENV').'/videos/' . $input['user_id'] . '/' . $fileName;

                Storage::put(
                            $storage_path,
                            file_get_contents($file->getRealPath())
                        );

                //$file->move($storage, $fileName);

            }

            // create testimonial
            $testimonial = new Testimonial([

                    'contact_id' => $input['contact_id'],
                    'rating' => $input['rating'],
                    'body' => $input['body'],
                    'video' => !empty($fileName) ? $fileName : "",
                    'video_type' => !empty($type) ? $type : "",
                    'storage_path' => !empty($storage_path) ? $storage_path : "",
                    'email' => $input['email']

                ]);

            $user = User::findOrFail($input['user_id']);

            $user->testimonials()->save($testimonial);

            $data = [
                'user' => $user,
                'testimonial' => $testimonial
            ];

            // mail the user
            Mail::send('emails.new_testimonial', $data, function($m) use ($user) {

                $m->to($user->email, $user->getName())->subject("New Testimonial");
            });

            return response()->json([
                    'message' => 'looks good'
                ], 201);

        } catch(\Exception $e) {

            return response()->json([
                    'error' => $e->getMessage()
                ], 500);

        }

    }

    /**
     * [showTestimonialVideo description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function showTestimonialVideo($id)
    {
        try {

            $testimonial = Testimonial::findOrFail($id);

            if(empty($testimonial->video)) {
                die("Video does not exist");
            }

            header("Content-Type: " . $testimonial->video_type);

            $video = Storage::get($testimonial->storage_path);

            Log::info('video from amazon', [gettype($video)]);

            echo $video;

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            echo $e->getMessage();

        } catch(\Exception $e) {

            echo $e->getMessage();
        }
    }

    /**
     * [destroy description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function destroy(Request $request)
    {
        $this->validate($request, [
                'id' => 'required|exists:testimonials,id',
            ]);

        try {

            $testimonial = Testimonial::findOrFail($request->get('id'));

            $contact = $testimonial->contact()->first();

            $data = [
                'contact_name' => $contact->getName(),
                'redirect_url' => url("contacts/".$contact->id."/email")
            ];

            return response()->json($data, 200);

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Could not find the testimonial'
            ], 404);

        } catch(\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
