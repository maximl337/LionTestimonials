<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use Session;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function getProfile()
    {
    	$user = Auth::user();

    	return view('users.show', compact('user'));
    }

    public function editProfile()
    {
    	$user = Auth::user();

    	return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();

        $user->update($request->input());

        Session::flash('message', 'Profile updated');

        return view('users.edit', compact('user'));
    }

    public function verify($token)
    {
        // get authenticated users token
        $user = Auth::user();

        if($token != $user->verification_token) {

            Session::flash('error', 'Given token did not match the one on record.');

            return redirect('/profile');
        }

        $user->verified_at = Carbon::now();

        $user->update();

        Session::flash('success', 'Thank you. Your account is now verified');

        return redirect('/profile');
    }

}
