<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use App\Http\Requests;
use App\SupportArticle as Article;

class SupportArticleController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth');

    	$this->middleware('admin', ['except' => ['index', 'show']]);

        $this->middleware('subscribed', ['only' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
    	$limit = $request->get('limit') ?: 1;

    	$offset = $request->get('offset') ?: 0;

    	$articles = Article::latest()->paginate($limit);

    	return view('articles.index', compact('articles'));

    }

    public function show($id)
    {
    	try {

    		$article = Article::findOrFail($id);

    		return view('articles.show', compact('article'));
    		
    	} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    		
    		Session::flash("error", $e->getMessage());

    		return redirect()->back();
    	}
    }

    public function create()
    {
    	return view('articles.create');
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    			'title' => 'required',
    			'body' => 'required'
    		]);

    	try {
    	
    		$input = $request->input();

	    	$article = new Article($input);

	    	Auth::user()->support_articles()->save($article);

	    	//Session::flash("success", "Article saved succussfully");

	    	return redirect()->action('SupportArticleController@show', [$article->id]);	

    	} catch (\Exception $e) {
    		
    		Session::flash("error", $e->getMessage());

    		return redirect()->back();
    	}

    }

    public function edit($id)
    {
    	try {
    		
    		$article = Article::findOrFail($id);

    		return view('articles.edit', compact('article'));

    	} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    		
    		return redirect()->back()->with("error", $e->getMessage());
    	}
    }

    public function update($id, Request $request)
    {
    	$this->validate($request, [
    			'title' => 'required',
    			'body' => 'required'
    		]);

    	try {
    		
    		$article = Article::findOrFail($id);

    		$article->update($request->input());

    		return redirect()->back()->with("success", "Article updated successfully");

    	} catch (\Exception $e) {
    		
    		return redirect()->back()->with("error", $e->getMessage());
    	}
    }

    public function destroy($id)
    {
    	try {
    		
    		$article = Article::findOrFail($id);

    		$article->delete();

    		return redirect()->action('SupportArticleController@index')->with("success", "Article deleted");

    	} catch (\Exception $e) {
    		
    		return redirect()->back()->with("errro", $e->getMessage());
    	}
    }
}
