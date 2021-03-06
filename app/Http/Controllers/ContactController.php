<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Log;
use File;
use Auth;
use Mail;
use Excel;
use Cache;
use Twilio;
use Storage;
use Session;
use App\User;
use App\Video;
use Validator;
use App\Contact;
use App\Branding;
use Carbon\Carbon;
use App\Invitation;
use App\Http\Requests;
use App\ContactImport;
use App\Contracts\GoogleApi;
use App\Contracts\MicrosoftApi;
use App\Services\ContactService;
use App\ThirdPartyTestimonialSite;
use App\Http\Requests\CreateContactRequest;
use App\Transformers\GoogleContactsImportTransformer;
use App\Transformers\OutlookContactsImportTransformer;

class ContactController extends Controller
{

    protected $contactService;

    protected $googleApi;

    protected $microsoftApi;

    public function __construct(ContactService $contactService, GoogleApi $googleApi, MicrosoftApi $microsoftApi)
    {
    	$this->middleware('auth', ['except' => ['getSelfRegister', 'selfRegister', 'googleOauthCallback']]);

        $this->middleware('contact.owner', ['only' => ['update', 'destroy']]);

        $this->middleware('verified', ['only' => ['sendExternalLinksEmail', 'externalLinksEmailPreview', 'sendSMS', 'sendEmailSelf', 'sendEmail', 'smsPreview', 'emailPreview']]);

        $this->middleware('subscribed', ['except' => ['getSelfRegister', 'googleOauthCallback', 'outlookOauthCallback']]);

        $this->contactService = $contactService;

        $this->googleApi = $googleApi;

        $this->microsoftApi = $microsoftApi;
    }

    /**
     * [index description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function index(Request $request)
    {
    	$limit = $request->get('limit') ?: 9;

        $page = $request->get('page') ?: 0;
    	
        $contacts = Auth::user()->contacts()->latest()->paginate($limit);
    	
        //$contacts = Auth::user()->contacts()->latest()->paginate($limit);

    	return view('contacts.index', compact('contacts'));
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $google_oauth_url = $this->googleApi->getOauthUrl();

        $outlook_oauth_url = $this->microsoftApi->getOauthUrl();

    	return view('contacts.create', compact('google_oauth_url', 'outlook_oauth_url'));
    }

    /**
     * [store description]
     * @param  CreateContactRequest $request [description]
     * @return [type]                        [description]
     */
    public function store(CreateContactRequest $request)
    {
        $input = $request->input();

        $contact = new Contact([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'phone' => !empty($input['phone']) ? $input['phone'] : ""
            ]);

        Auth::user()->contacts()->save($contact);

        Session::flash('success', 'Contact created. Now preview and send them an invitation');

        return redirect('contacts');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        try {

            $contact = Contact::findOrFail($id);

            return view('contacts.edit', compact('contact'));

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact not found');

            return redirect()->back();

        } catch(\Exception $e) {

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
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|email|unique:contacts,email,'. $id .',id,user_id,' . Auth::id(),
                'phone' => 'phone:AUTO,US',
            ],
            [
                'email.unique' => "You already have a contact with that email"
            ]);

        try {

            $contact = Contact::findOrFail($id);

            $contact->update($request->input());

            Session::flash('success', 'Contact updated');

            return redirect()->back();

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact not found');

            return redirect()->back();

        } catch(\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

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

            $contact = Contact::findOrFail($id);

            $contact->delete();

            return response()->json([
                'message' => 'Contact deleted'], 200);

        } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'message' => 'Contact not found'], 404);

        } catch(\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()], 500);

        }
        
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function import()
    {
        return view('contacts.import');
    }

    /**
     * [importCsv description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function importCsv(Request $request)
    {
    	$this->validate($request, [
                'csv' => 'required',
                'g-recaptcha-response' => 'required|recaptcha',
            ]);

        if ($request->hasFile('csv')) {

            try {

                // get file
                $file = $request->file('csv');

                // define storage path
                $destinationPath = storage_path('contact_imports');

                // make file name
                $fileName = Auth::id() . '-' . microtime(true) . '-' . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

                Storage::put(
                        'lion-testimonials/user-csv/' . Auth::id() . '/' . $fileName,
                        file_get_contents($file->getRealPath())
                    );

                // move file
                $file->move($destinationPath, $fileName);

                $error = false;
                
                // start parsing excel
                Excel::load($destinationPath . '/' . $fileName, function($reader) use ($destinationPath, $fileName, &$error) {

                    $firstRow = $reader->first()->toArray();

                    if( !empty($firstRow['firstname']) && 
                        !empty($firstRow['lastname']) &&
                        !empty($firstRow['email'])) {

                        // Getting all results
                        $results = $reader->get(['firstname', 'lastname', 'email', 'phone']);

                        $message = $this->storeContacts($results);

                        Session::flash('success', 'Contacts imported. ' . $message);

                        File::delete($destinationPath . '/' . $fileName);
                        
                    } else {

                        Session::flash('error', 'Required columns not found');

                        File::delete($destinationPath . '/' . $fileName);

                        $error = true;

                    }

                }); // EO Excel load

            } catch(\Maatwebsite\Excel\Exceptions\LaravelExcelException $e) {

                Session::flash('error', $e->getMessage());

                $error = true;

            } catch(\Exception $e) {

                Session::flash('error', $e->getMessage());

                $error = true;

            }

            if($error) {
                return redirect()->back();
            } 

            return redirect('/contacts');

        }

    }

    /**
     * [importCsv description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function importVendor(Request $request)
    {
        $this->validate($request, [
                'contact_imports' => 'required|array',
            ]);

        

        try {

            $input = $request->input();

            $user = Auth::user();

            foreach($input['contact_imports'] as $contact_import_id) {

                $contact_import = ContactImport::find($contact_import_id);

                if(!$contact_import) {
                    continue;
                }

                $exists = $user->contacts()->where('email', $contact_import->email)->exists();

                if($exists) {
                    continue;
                }

                $user->contacts()->save(new Contact([
                    'first_name' => $contact_import->first_name,
                    'last_name' => $contact_import->last_name,
                    'email' => $contact_import->email,
                    'phone' => $contact_import->phone ?: ""
                ]));

            }

            Session::flash('success', 'Contacts imported');

            return redirect()->action('ContactController@index');

        } catch(\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->action('ContactController@index');

        }


    }

    /**
     * [storeContacts description]
     * @param  [type] $contacts [description]
     * @return [type]           [description]
     */
    public function storeContacts($contacts)
    {

        $message = [];

        if(!is_null($contacts)) {

            foreach($contacts as $contact) {

                $emailValidator = Validator::make([
                        'email' => $contact->email
                    ], [
                        'email' => 'email|unique:contacts,email,NULL,id,user_id,' . Auth::id()
                    ]);

                if($emailValidator->fails()) {

                    $message[] = $contact->email . ' is not a valid email or has already been invited';
                    
                    continue;
                }

                $newContact = new Contact([
                        'first_name' => $contact->firstname,
                        'last_name' => $contact->lastname,
                        'email' => $contact->email,
                        'phone' => !empty($contact->phone) ? $contact->phone : ''
                    ]);

                Auth::user()->contacts()->save($newContact);

            } // eo foreach

        } // endif

        if(count($message) > 0) {
            return " Some contacts could not be imported due to an invalid email";
        }

        return false;
    }

    /**
     * Preview of the email to be sent
     * 
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function emailPreview($id, Request $request)
    {
        try {

            $contact = Contact::findOrFail($id);

            $video_id = $request->get('video_id');

            $videos = Auth::user()->videos()->get(['title', 'id']);
            
            return view('contacts.email', compact('contact', 'videos', 'video_id'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact with the given id does not exist');

            return redirect()->back();
            
        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * [smsPreview description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function smsPreview($id)
    {
        try {

            $contact = Contact::findOrFail($id);
            
            return view('contacts.sms', compact('contact'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact with the given id does not exist');

            return redirect()->back();
            
        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }
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
                'message'   => 'required' 
            ]);

        try {

            $input = $request->input();

            $contact = Contact::findOrFail($input['contact_id']);

            $token = md5(uniqid(Auth::user()->email . env('APP_KEY'), true));

            $video = false;

            $params = [
                'token' => $token,
                'id' => $contact->id
            ];

            if(!empty($input['video_id'])) {
                $video = Video::find($input['video_id']);
            }
            

            //make url
            $url = env('APP_URL') . 'testimonials/create?' . http_build_query($params);

            $data = [
                'url' => $url,
                'body' => $input['message'] . " ",
                'video' => $video ?: false,
                'user' => Auth::user()
            ];

            // send mail
            Mail::send('emails.invite', $data, function($m) use ($contact) {

                $m->from('robot@sellwithreviews.com', Auth::user()->getName());

                $m->to($contact->email, $contact->first_name)->subject('Testimonial Request');
            });

            $invitation = new Invitation([
                    'email' => true,
                    'token' => $token
                ]);

            $contact->invitation()->save($invitation);

            Session::flash('success', 'Email sent successfully');

            return redirect('/contacts');


        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact with the given id does not exist');

            return redirect()->back();
            
        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }

    }

    /**
     * [sendEmailSelf description]
     * @return [type] [description]
     */
    public function sendEmailSelf(Request $request)
    {

        try {

            $user = Auth::user();

            $input = $request->input();

            $video = false;

            if(!empty($input['video_id'])) {
                $video = Video::find($input['video_id']);
            }

            $data = [
                'url' => "#",
                'body' => $input['message'] . " ",
                'video' => $video ?: false,
                'user' => Auth::user()
            ];

            // send mail
            Mail::send('emails.invite', $data, function($m) use ($user) {
                $m->to($user->email, $user->first_name)->subject('Email preview');
            });

            Session::flash('success', 'Email sent successfully');

            return redirect()->back();


        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact with the given id does not exist');

            return redirect()->back();
            
        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }

        
    }

    /**
     * [sendSMS description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sendSMS(Request $request)
    {
        $this->validate($request, [
                'contact_id' => 'required|exists:contacts,id'   
            ]);

        try {

            $input = $request->input();

            // get contact
            $contact = Contact::findOrFail($input['contact_id']);

            if(empty($contact->phone)) {
                throw new Exception('Contact does not have a phone number on record');
            }

            $token = $contact->token;

            if(is_null($token)) {
                
                //make token
                $token = md5(uniqid(Auth::user()->email . env('APP_KEY'), true));

            }
            
            $params = [
                'token' => $token,
                'id' => $contact->id
            ];

            //make url
            $url = env('APP_URL') . 'testimonials/create?' . http_build_query($params);

            $message = 'Hi ' . $contact->first_name . ', '. Auth::user()->getName() .' has requested a testimonial from you for his services. This should take no more than a couple of minutes. Click the link below to send the testimonial. '. $url;

            Twilio::message($contact->phone, $message);

            $invitation = new Invitation([
                    'sms' => true,
                    'token' => $token
                ]);

            $contact->invitation()->save($invitation);

            Session::flash('success', 'SMS sent successfully');

            return redirect('/contacts');


        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact with the given id does not exist');

            return redirect()->back();
            
        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * [externalLinksEmailPreview description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function externalLinksEmailPreview($id)
    {
        try {

            $contact = Contact::findOrFail($id);

            $links = Auth::user()->thirdPartyTestimonialSites()->get();
            
            return view('contacts.external_links_email_preview', [
                    'contact' => $contact,
                    'links' => $links 
                ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', 'Contact with the given id does not exist');

            return redirect()->back();
            
        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * [sendExternalLinksEmail description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function sendExternalLinksEmail(Request $request)
    {
        $this->validate($request, [
                'contact_id' => 'required|exists:contacts,id',
                'message'   => 'required',
                'links'     => 'required'
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

            Session::flash('success', 'Email sent successfully');

            return redirect('/contacts');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();
            
        } catch (\Exception $e) {

            Session::flash('error', $e->getMessage());

            return redirect()->back();

        }
    }

    /**
     * [getSelfRegister description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getSelfRegister($id)
    {
        try {
            
            $user = User::findOrFail($id);

            $branding = $user->branding()->first();

            if(!$branding) {
                $branding = new Branding();
            }

            return view('contacts.register', compact('user', 'branding'));  

        } catch (Exception $e) {
                
            Session::flash('error', $e->getMessage());  

            return view('contacts.register');

        }
    }

    /**
     * [selfRegister description]
     * @param  [type]  $id      [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function selfRegister($id, Request $request)
    {
        try {

            $this->validate($request, [
                    'first_name' => 'required',
                    'last_name'  => 'required',
                    'email'     => 'required|email|unique:contacts,email,NULL,id,user_id,' . $id,
                    'phone'     => 'phone:AUTO,US' //'digits:10'
                ]);
            
            $user = User::findOrFail($id);

            $input = $request->only(['first_name', 'last_name', 'email', 'phone']);

            $input = array_filter($input, 'strlen');

            $contact = new Contact($input);

            $user->contacts()->save($contact);

            $this->contactService->sendEmailInvite($contact, $user);

            Session::flash('success', 'Thank you for registering. We will be in touch soon');

            return redirect()->back();

        } catch (\Exception $e) {
            
            Session::flash('error', 'Unable to register due to an internal error. Please try again');

            return redirect()->back();
        }
    }

    /**
     * Google OAuth sends user back here
     *
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function googleOauthCallback(Request $request, GoogleContactsImportTransformer $googleContactImportTransformer)
    {
        try {
            
            $input = $request->input();

            if(!empty($input['error'])) {
                throw new \Exception($input['error']);
            }

            if(empty($input['code'])) {
                throw new \Exception("Google did not provide an authorization code");
            }

            $code = $input['code'];

            // get access token
            $access_token = $this->googleApi->getAccessToken($code);

            $email = $this->googleApi->getUserEmail($access_token);

            $ugly_contacts = $this->googleApi->getUserContacts($access_token, $email);

            if(empty($ugly_contacts['feed']['entry'])) {
                throw new \Exception("Google did not return any contacts!");
            }

            $google_contacts = $googleContactImportTransformer->transformCollection($ugly_contacts['feed']['entry']);

            $google_contacts = array_filter($google_contacts);

            $contacts = [];

            $user_id = Auth::id();

            foreach($google_contacts as $contact) {
                
                $contact['provider'] = 'google';

                $contact['user_id'] = $user_id;

                // chheck if exists
                $exists = ContactImport::where('email', $contact['email'])->where('user_id', $user_id)->exists();

                if($exists) {
                    continue;
                }

                $contacts[] = ContactImport::create($contact);
            }

            return view('contacts.import', compact('contacts'));

        } catch (\Exception $e) {

            Session::flash('error', "Could not import contacts from Google: " . $e->getMessage());

            $google_oauth_url = $this->googleApi->getOauthUrl();

            $outlook_oauth_url = $this->microsoftApi->getOauthUrl();

            return redirect()->action('ContactController@create', compact('google_oauth_url', 'outlook_oauth_url'));
        }
    }

    /**
     * [FunctionName description]
     * @param string $value [description]
     */
    public function outlookOauthCallback(Request $request, OutlookContactsImportTransformer $outlookContactsImportTransformer)
    {
        try {
            
            $input = $request->input();

            if(!empty($input['error'])) {
                throw new \Exception($input['error']);
            }

            if(empty($input['code'])) {
                throw new \Exception("Microsoft did not provide an authorization code");
            }

            $code = $input['code'];

            // get access token
            $access_token = $this->microsoftApi->getAccessToken($code);

            $ugly_contacts = $this->microsoftApi->getUserContacts($access_token);

            if(empty($ugly_contacts['value'])) {
                throw new \Exception("Microsoft did not return any contacts!");
            }

            $outlook_contacts = $outlookContactsImportTransformer->transformCollection($ugly_contacts['value']);

            $outlook_contacts = array_filter($outlook_contacts);

            $contacts = [];

            $user_id = Auth::id();

            foreach($outlook_contacts as $contact) {
                
                $contact['provider'] = 'outlook';

                $contact['user_id'] = $user_id;

                // chheck if exists
                $exists = ContactImport::where('email', $contact['email'])->where('user_id', $user_id)->exists();

                if($exists) {
                    continue;
                }

                $contacts[] = ContactImport::create($contact);
            }

            return view('contacts.import', compact('contacts'));

        } catch (\Exception $e) {

            Session::flash('error', "Could not import contacts from Outlook: " . $e->getMessage());

            $google_oauth_url = $this->googleApi->getOauthUrl();

            $outlook_oauth_url = $this->microsoftApi->getOauthUrl();

            return redirect()->action('ContactController@create', compact('google_oauth_url', 'outlook_oauth_url'));
        }
    }
    
}
