<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use App\Category;
use App\Testimonial;
use App\Http\Requests;

class CategoryController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    			'testimonial_id' => 'required|exists:testimonials,id',
    			'category_name' => 'required_without:category_id|max:255',
    			'category_id' => 'required_without:category_name|exists:categories,id'
    		]);

    	try {

    		$input = $request->input();

    		$category = false;

	    	if(!empty($input['category_name'])) {

	    		$category = new Category([
	    				'name' => $input['category_name'],
	    				'user_id' => Auth::id()
	    			]);
	    		

	    	} elseif(!empty($input['category_id'])) {

	    		$category = Category::findOrFail($input['category_id']);

	    	} else {
	    		throw new Exception("Category could not be saved");
	    	}

	    	$testimonial = Testimonial::findOrFail($input['testimonial_id']);

	    	$testimonial->categories()->detach();

	    	$testimonial->categories()->save($category);

	    	Session::flash("success", "Category saved");

	    } catch(\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

	    	Session::flash("error", $e->getMessage());

    	} catch (Exception $e) {
    		
    		Session::flash("error", $e->getMessage());

    	}

    	return redirect()->back();
    	
    }
}
