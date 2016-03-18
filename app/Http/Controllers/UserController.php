<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use Session;
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
}
