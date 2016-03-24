<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cache;
use Auth;
use App\User;
use Session;
use App\Services\ImageService;
use App\Services\ImageHandler;
use Carbon\Carbon;
use App\Http\Requests;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    protected $imageService;

    public function __construct(ImageService $imageService)
    {
    	$this->middleware('auth');

        $this->imageService = $imageService;
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

        if($request->hasFile('picture')) {

           //$user->picture = $this->imageService->upload( $request->file('picture')->getRealPath() );
            $user->picture = (new ImageHandler)->save($request->file('picture'));
        }

        if($request->hasFile('business_logo')) {

           //$user->business_logo = $this->imageService->upload( $request->file('business_logo')->getRealPath() );
            $user->business_logo = (new ImageHandler)->save($request->file('business_logo'), 'business_logo');
        }

        $user->save();

        Session::flash('message', 'Profile updated');

        return redirect()->back();

        // return reponse()->json([
        //         'message' => 'profile updated'
        //     ], 200);
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
