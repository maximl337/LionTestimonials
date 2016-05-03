<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\User;
use App\Video;
use App\Http\Requests;

class VideoController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['show']]);
	}

    public function index(Request $request)
    {
    	$limit = $request->get('limit') ?: 3;

    	$page = $request->get('page') ?: 0;

    	$videos = Video::latest()->paginate($limit);

    	return view('videos.index', compact('videos'));
    }

    public function show($id)
    {
    	try {
    		
    		$video = Video::findOrFail($id);

    		return view('videos.show', compact('video'));

    	} catch (\Exception $e) {
    		
    		return redirect()->back()->with("error", $e->getMessage());

    	}
    }

    public function create()
    {
    	return view('videos.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    			'title' => 'required|max:255',
    			'token' => 'required'
    		],
    		[
    			'token.required' => 'We were unable to store the video. Try refreshing the page and trying again'
    		]);

    	try {
    		
    		$video  = new Video([
    			
	    			'token' => $request->get('token'),
	    			'title' => $request->get('title')

	    			]);

    		Auth::user()->videos()->save($video);

    		return redirect()->action('VideoController@show', ['id' => $video->id])->with("success", "Video saved successfully");

    	} catch (\Exception $e) {
			
			return redirect()->back()->with("error", $e->getMessage());

    	}

    }

    public function destroy($id)
    {
    	try {
    		
    		$video = Video::findOrFail($id);

    		$video->delete();

    		return response("Video deleted successfully", 200);

    	} catch (\Exception $e) {
    		
    		return response($e->getMessage(), 500);
    	}
    }
}
