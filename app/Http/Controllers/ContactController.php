<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use File;
use Auth;
use Excel;
use Validator;
use App\Contact;
use Session;
use App\Http\Requests\CreateContactRequest;
use App\Http\Requests;

class ContactController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function index(Request $request)
    {
    	$limit = $request->get('limit') ?: 9;

        $page = $request->get('page') ?: 0;
    	
    	$contacts = Auth::user()->contacts()->latest()->paginate($limit);

    	return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
    	return view('contacts.create');
    }

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

    public function import()
    {
        return view('contacts.import');
    }

    public function importCsv(Request $request)
    {
    	$this->validate($request, [
                'csv' => 'required'
            ]);

        if ($request->hasFile('csv')) {

            try {

                // get file
                $file = $request->file('csv');

                // define storage path
                $destinationPath = storage_path('contact_imports');

                // make file name
                $fileName = Auth::id() . '-' . microtime(true) . '-' . md5($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

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

    
}
