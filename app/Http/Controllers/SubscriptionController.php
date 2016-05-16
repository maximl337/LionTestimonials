<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Http\Requests;

class SubscriptionController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index()
    {
    	return view('billing');
    }

    public function subscribe(Request $request)
    {
    	try {

    		$input = $request->input();

	    	$user = Auth::user();

	    	$user->newSubscription('primary', 'lion')->create($input['stripeToken'], [
	    			'email' => $user->email,

	    		]);

	    	return redirect('home')->with("success", "Thank you. You are now subscribed!");

    	} catch(\Exception $e) {

    		return redirect()->back()->with("error", $e->getMessage());
    	}
    	
    }

    public function resume(Request $request)
    {
    	try {
    		
    		Auth::user()->subscription('primary')->resume();

    		return redirect('home')->with("success", "Thank you. Your subscription is resumed");

    	} catch (\Exception $e) {
    		
    		return redirect()->back()->with("error", $e->getMessage());
    	}
    }

    public function cancel()
    {
    	try {
    		
    		Auth::user()->subscription('primary')->cancel();

    		return redirect('home')->with("subscription_cancelled", "Subscription cancelled successfully");

    	} catch (\Exception $e) {
    		
    		return redirect()->back()->with("error", $e->getMessage());
    	}
    }
}
