<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Excel;
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

            $file = $request->file('csv');

            $destinationPath = storage_path('contact_imports');

            $fileName = Auth::id() . '-' . microtime(true) . '-' . $file->getClientOriginalName();

            $file->move($destinationPath, $fileName);
            
            Excel::load(storage_path('contact_imports') . '/' . $fileName, function($reader) {

                // Getting all results
                $results = $reader->get();
                dd($results);

                // ->all() is a wrapper for ->get() and will work the same
                //$results = $reader->all();

            });

        }

        dd("Did not get file");

        
    }

    
}
