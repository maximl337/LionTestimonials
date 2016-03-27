<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Mail;
use Session;
use App\User;
use App\Contact;
use Carbon\Carbon;
use App\Testimonial;
use App\Http\Requests;

class TestimonialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'store']]);

        $this->middleware('testimonial.owner', ['only' => ['approve']]);

    }

    public function getTestimonials(Request $request)
    {
        $limit = $request->get('limit') ?: 9;

        $page = $request->get('page') ?: 0;

        $filter = $request->get('filter') ?: 'all';

        if($filter == 'approved') {

            $testimonials = Auth::user()->testimonials()->approved()->with('contact')->paginate($limit);
        }
        elseif ($filter == 'unapproved') {

            $testimonials = Auth::user()->testimonials()->unapproved()->with('contact')->paginate($limit);
        }
        else {

            $testimonials = Auth::user()->testimonials()->with('contact')->paginate($limit);
        }

        return view('testimonials.index', compact('testimonials'));
    }

    public function getTestimonial($id, Request $request)
    {
        try {

            $testimonial = Testimonial::findOrFail($id);

            return view('testimonials.show', compact('testimonial'));

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
    			'token' => 'required|exists:contacts,token',
    		], [
    			'token.exists' => "The given token was not found in our records",
    			'id.exists' => "The given id was not found in our records",
    			'id.required' => "ID not found to create a testimonial",
    			'token.require' => "Token not found"
    		]);

    	$input = $request->input();

    	try {

    		$contact = Contact::where('id', $input['id'])->where('token', $input['token'])->firstOrFail();

    		$user = $contact->user()->first();

    		// check if token matches
    		if($contact->token != $input['token']) {
    			throw new \Exception("Given token does not match the one on record");
    		}

    		$data = [
    			'contact' => $contact,
    			'user' => $user
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
    public function store(Request $request)
    {
    	$this->validate($request, [

    			'contact_id' => 'required|exists:contacts,id',
    			'user_id' => 'required|exists:contacts,user_id|exists:users,id',
    			'rating' => 'integer|max:5|min:1',
    			'email' => 'email|required',
                'g-recaptcha-response' => 'required|recaptcha',

    		]);

    	$input = $request->input();

    	$exists = Testimonial::where('contact_id', $input['contact_id'])->where('user_id', $input['user_id'])->exists();

    	if($exists) {

    		Session::flash('error', 'You have already added a testimonial');

    		return redirect()->back();
    	}

    	// create testimonial
    	$testimonial = new Testimonial([

    			'contact_id' => $input['contact_id'],
    			'rating' => $input['rating'],
    			'body' => $input['body'],
    			'video' => !empty($input['video']) ? $input['video'] : "",
    			'email' => $input['email']

    		]);

    	$user = User::findOrFail($input['user_id']);

    	$user->testimonials()->save($testimonial);

    	$data = [
    		'user' => $user
    	];

    	// mail the user
    	Mail::send('emails.new_testimonial', $data, function($m) use ($user) {
    		$m->from('hello@lion.com', 'Lion Testimonials');

    		$m->to($user->email, $user->getName())->subject("New Testimonial");
    	});


    	Session::flash('success', 'Testimonial Created. Thank you.');

    	return redirect()->back();
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

            Testimonial::findOrFail($request->get('id'))->update(['approved_at' => Carbon::now()]);

            return response()->json([
                'message' => 'Testimonial updated'
            ], 200);

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Could not find the testimonial'
            ], 404);

        } catch(\Exception $e) {

            return response()->json([
                'message' => 'Internal server error'
            ], 500);
        }
        
    }

    public function saveDesktopVideo(Request $request)
    {
        try {

            foreach(array('video', 'audio') as $type) {
                
                if ( $request->hasFile("${type}-blob") ) {

                    $file = $request->file("${type}-blob");

                    $fileName = Auth::id() . '-' . uniqid(rand()) . '-' . $file->getClientOriginalName();

                    $destinationPath = storage_path('media');

                    $file->move($destinationPath, $fileName);
                }
            }

            return response()->json([
                    'message' => 'looks good'
                ], 201);

        } catch(\Exception $e) {

            return response()->json([
                    'error' => $e->getMessage()
                ], 500);

        }

        
    }

    public function showVid()
    {
        
        header("Content-Type: video/webm");

        echo file_get_contents(storage_path('media') . '/' . '1-2741556f7fb7758323-blob');
    }
}
