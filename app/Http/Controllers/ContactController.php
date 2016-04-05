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
use Validator;
use App\Contact;
use Carbon\Carbon;
use App\Invitation;
use App\Http\Requests;
use App\Http\Requests\CreateContactRequest;

class ContactController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');

        $this->middleware('contact.owner', ['only' => ['update', 'destroy']]);
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
    	return view('contacts.create');
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
                'phone' => $input['phone']
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
                'phone' => 'digits:10'
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
                'message' => $e->getMessage], 500);

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
    public function emailPreview($id)
    {
        try {

            $contact = Contact::findOrFail($id);
            
            return view('contacts.email', compact('contact'));

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

            $params = [
                'token' => $token,
                'id' => $contact->id
            ];

            //make url
            $url = env('APP_URL') . 'testimonials/create?' . http_build_query($params);

            $data = [
                'url' => $url,
                'body' => $input['message'] . " "
            ];

            // send mail
            Mail::send('emails.invite', $data, function($m) use ($contact) {
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
    public function sendEmailSelf()
    {

        try {

            $user = Auth::user();

            $data = [
                'user' => $user,
                'contact' => $user,
                'url' => "#"
            ];

            // send mail
            Mail::send('emails.invite', $data, function($m) use ($user) {
                $m->to($user->email, $user->first_name)->subject('Account verification');
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
                throw new Exception('Contact does not have a phone numbe ron record');
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

            // $data = [
            //     'user' => Auth::user(),
            //     'contact' => $contact,
            //     'url' => $url
            // ];

            // // send mail
            // Mail::send('emails.invite', $data, function($m) use ($contact) {
            //     $m->from('hello@lion.com', 'Lion Testimonials');
            //     $m->to($contact->email, $contact->first_name)->subject('Testimonial Request');
            // });

            $message = 'Hi ' . $contact->first_name . ', '. Auth::user()->getName() .' has requested a testimonial from you for his services. This should take no more than a couple of minutes. Click the link below to send the testimonial. '. $url ;

            Twilio::message($contact->phone, $message);

            // update contact
            $contact->update([
                    'token' => $token,
                    'sms_sent_at' => Carbon::now()
                ]);

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
    
}
