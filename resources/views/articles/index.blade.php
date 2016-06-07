@extends('layouts.app')

@section('head')

<style type="text/css">
	article {
		padding-bottom: 20px;
	}
</style>
@endsection

@section('content')


<div class="panel panel-default">
	<div class="panel-heading">
		Knowledge Base
		@if(Auth::user()->isAdmin())
		<span class="pull-right">
			<a href="{{ url('support/create') }}">create</a>
		</span>
		@endif
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		@foreach($articles as $article)
		<article>
			
			<h3>
				{{ $article->title }}
				@if(Auth::user()->isAdmin() && $article->isOwner(Auth::id()))
				<a title="edit" class="text-small" style="font-size: 18px;" href="{{ url('support/' . $article->id) }}"> <i class="fa fa-pencil"></i></a>
				@endif
			</h3>
			
			<hr />
			<p>{{ $article->body }}</p>
			
		</article>
		<!-- /article -->
		@endforeach

		{!! $articles->render() !!}
	</div>
	<!-- /.panel-body -->
</div>

@endsection

