@extends('layouts.app')

@section('content')


<div class="panel panel-default">
	<div class="panel-heading">
		
		Update
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		
		<form id="create_third_party_site_links" method="POST" action="{{ url('externalLinks/'.$link->id) }}" role="form">

			{!! csrf_field() !!}
			
			<div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
				<label for="url">url for the site</label>
				<p class="help-block">
					Enter a link that will direct customers to your profile on third party testimonial sites.
				</p>
				<!-- /.help-block -->
				<input id="url" class="form-control " type="text" name="url" value="{{ $link->url }}" placeholder="www.yelp.com/yourProfile" required />
				@if ($errors->has('url'))
				<span class="help-block">
					<strong>{{ $errors->first('url') }}</strong>
				</span>
				@endif
			</div>
			
			<div class="form-group{{ $errors->has('provider') ? ' has-error' : '' }}">
				<label for="provider">Provider</label>
				<input id="provider" class="form-control" type="text" name="provider" value="{{ $link->provider }}" placeholder="Yelp" required />
				@if ($errors->has('provider'))
				<span class="help-block">
					<strong>{{ $errors->first('provider') }}</strong>
				</span>
				@endif
			</div>

			<div class="form-group">
				<input type="submit" class="form-control btn btn-primary" value="Update">
			</div>
		</form>
		
	</div>
	<!-- /.panel-body -->
</div>
@endsection