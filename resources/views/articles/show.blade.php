@extends('layouts.app')

@section('content')


<div class="panel panel-default">
	<div class="panel-heading">
		Preview
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<article>
			<h3>{{ $article->title }}</h3>
			<hr />
			<p>{{ $article->body }}</p>	
		</article>
		<hr />
		<div class="row">
			<div class="col-md-4"><a href="{{ url('support') }}" class="form-control btn btn-success">Save</a></div>
			<div class="col-md-4"><a href="{{ url('support/' . $article->id . '/edit') }}" class="form-control btn btn-primary">Edit</a></div>
			<div class="col-md-4">
				<form method="POST" action="{{ url('support/' . $article->id) }}" role="form">
					{!! csrf_field() !!}
					<input type="hidden" name="_method" value="DELETE">
					<div class="form-group">
						<input type="submit" class="form-control btn btn-danger" value="Delete" />
					</div>
				</form>
				
				
			</div>
		</div>
	</div>
	<!-- /.panel-bodu -->
</div>

@endsection