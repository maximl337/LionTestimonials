<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use Auth;
use Mail;
use App\User;
use App\Video;
use App\Contact;
use App\Http\Requests;
use App\Contracts\VideoToGif;

class VideoController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth', ['except' => ['show', 'convertToGif', 'saveConvertedGif']]);

        $this->middleware('subscribed', ['except' => ['show', 'convertToGif', 'saveConvertedGif']]);
	}

    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
    	$limit = $request->get('limit') ?: 3;

    	$page = $request->get('page') ?: 0;

    	$videos = Video::latest()->paginate($limit);

    	return view('videos.index', compact('videos'));
    }

    /**
     * [show description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function show($id)
    {
    	try {
    		
    		$video = Video::findOrFail($id);

    		return view('videos.show', compact('video'));

    	} catch (\Exception $e) {
    		
    		return redirect()->back()->with("error", $e->getMessage());

    	}
    }

    /**
     * [create description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function create(Request $request)
    {

        $testimonial_request = $request->get('testimonial_request');

        $contact_id = $request->get('contact_id');

    	return view('videos.create', compact('testimonial_request', 'contact_id'));
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
    	$this->validate($request, [
    			'title' => 'required|max:255',
    			'token' => 'required',
                'thumbnail' => 'required',
                'url' => 'required'
    		],
    		[
    			'token.required' => 'We were unable to store the video. Try refreshing the page and trying again'
    		]);

    	try {

            // if making profile video
            // remove all other profile videos
            // As users can only have one profile video
            if($request->get('profile_video')) {
                $videos = Auth::user()->videos()->where('profile_video', true)->get();

                foreach($videos as $video) {
                    $video->update(['profile_video' => false]);
                }    
            }
            
    		
    		$video  = new Video([
    			
	    			'token' => $request->get('token'),
	    			'title' => $request->get('title'),
                    'thumbnail' => $request->get('thumbnail'),
                    'url'       => $request->get('url'),
                    'profile_video' => $request->get('profile_video') ? true : false

	    			]);

    		Auth::user()->videos()->save($video);

            // wants to send request
            if($request->get('testimonial_request') && $request->get('contact_id')) {
                return redirect()
                        ->action('ContactController@emailPreview', 
                                ['id' => $request->get('contact_id'),
                                 'video_id' => $video->id])
                        ->with("success", "Video attached to request");
            }

    		return redirect()->action('VideoController@show', ['id' => $video->id])->with("success", "Video saved successfully");

    	} catch (\Exception $e) {
			
			return redirect()->back()->with("error", $e->getMessage());

    	}

    }

    /**
     * [destroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
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

    /**
     * [videoByEmailTemplate description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function videoByEmailTemplate($id)
    {
        try {

            $contacts = Auth::user()->contacts()->get();
            
            $video = Video::findOrFail($id);

            $data = [
                'contacts' => $contacts,
                'video' => $video
            ];

            return view('videos.email_template', compact('data'));

        } catch (\Exception $e) {
            
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * [sendByEmail description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sendByEmail(Request $request)
    {
        $this->validate($request, [
                'contact_id' => 'required|exists:contacts,id',
                'message' => 'required',
                'video_id' => 'required|exists:videos,id'
            ]);

        try {

            $input = $request->input();
            
            $contact = Contact::findOrFail($input['contact_id']);

            $video = Video::findOrFail($input['video_id']);

            $msg = $input['message'];

            $data = [
                'contact' => $contact,
                'video'  => $video,
                'body'   => $msg
            ];

            // mail
            Mail::send('emails.send_video', $data, function($m) use ($contact) {
                $m->to($contact->email, $contact->first_name)->subject('Video message');
            });

            return redirect()->action('VideoController@index')->with('success', 'Email sent successfully');

        } catch (Exception $e) {
            
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * [makeProfileVideo description]
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function makeProfileVideo($id, Request $request)
    {
        try {

            $video = Video::findOrFail($id);

            // remove all other profile videos
            $videos = Auth::user()->videos()->where('profile_video', true)->get();

            foreach($videos as $vid) {
                $vid->update(['profile_video' => false]);
            }

            $video->update(['profile_video' => true]);

            return redirect()->back()->with("success", "Marked as profile video");

        } catch (Exception $e) {

            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * [convertToGif description]
     * @param  Request    $request [description]
     * @param  VideoToGif $client  [description]
     * @return [type]              [description]
     */
    public function convertToGif(Request $request, VideoToGif $client)
    {
        $this->validate($request, [
                'url' => 'required|url'
            ]);

        try {

            $input = $request->input();

            $client->convert($input['url']);

            Log::info("grabzit", ["message" => "Video send to be converted"]);

            return response(["message" => "Sent to convert"], 200);

       } catch (Exception $e) {
           
           Log::info("grabzit", ["message" => $e->getMessage()]);

           return response(["message" => $e->getMessage()], 500);
       }   
    }

    /**
     * [saveConvertedGif description]
     * @param  Request    $request [description]
     * @param  VideoToGif $client  [description]
     * @return [type]              [description]
     */
    public function saveConvertedGif(Request $request, VideoToGif $client)
    {
        
        try {
            
            $client->save($request->input());

            Log::info("grabzit", ["message" => "Video saved"]);

        } catch (Exception $e) {

            Log::info("grabzit", ["message" => $e->getMessage()]);

        }

        return response([], 200);
    }
}
