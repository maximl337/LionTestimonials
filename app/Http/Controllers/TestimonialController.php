<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Mail;
use Session;
use App\User;
use App\Contact;
use App\Testimonial;
use App\Http\Requests;

class TestimonialController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'store']]);
    }

    public function getTestimonials(Request $request)
    {
        $limit = $request->get('limit') ?: 9;

        $page = $request->get('page') ?: 0;

        $testimonials = Auth::user()->testimonials()->with('contact')->paginate($limit);

        return view('testimonials.index', compact('testimonials'));
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
}
