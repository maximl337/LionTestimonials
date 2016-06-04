@extends('layouts.app')

@section('content')

<div class="panel panel-default">
	<div class="panel-heading">
		Create article
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<form id="edit-article" method="POST" action="{{ url('support') }}" role="form">

			{!! csrf_field() !!}

			<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
				<label for="">Title</label>
				<input id="title" class="form-control " type="text" name="title" value="{{ old('title') }}" placeholder="Enter title" />
				@if($errors->has('title'))
				<span class="help-block">
					<strong>{{ $errors->first('title') }}</strong>
				</span>
				@endif
			</div>

			<div class="form-group{{ $errors->has('body' ? ' has-error' : '') }}">
				<label for="body">Body</label>
				<textarea id="body" class="form-control" name="body" rows="10" placeholder="Enter body">{{ old('body') }}</textarea>
				@if ($errors->has('body'))
				<span class="help-block">
					<strong>{{ $errors->first('body') }}</strong>
				</span>
				@endif
			</div>

			<div class="form-group">
				<input type="submit" class="form-control btn btn-primary" value="Preview">
			</div>
			
		</form>
	</div>
	<!-- /.panel-body -->
</div>

@endsection