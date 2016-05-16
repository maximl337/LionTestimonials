@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-offset-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					
					Add Third Party Testimonial Sites Links
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					
					<form id="create_third_party_site_links" method="POST" action="{{ url('externalLinks') }}" role="form">

						{!! csrf_field() !!}
						 
						<div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
							<label for="url">url for the site</label>
							<p class="help-block">
								Enter a link that will direct customers to your profile on third party testimonial sites.
							</p>
							<!-- /.help-block -->
							<input id="url" class="form-control " type="text" name="url" value="{{ old('url') }}" placeholder="www.yelp.com/yourProfile" required />
							@if ($errors->has('url'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('url') }}</strong>
                                </span>
                            @endif
						</div>
						
						<div class="form-group{{ $errors->has('provider') ? ' has-error' : '' }}">
							<label for="provider">Provider</label>
							<input id="provider" class="form-control" type="text" name="provider" value="{{ old('provider') }}" placeholder="Yelp" required />
							@if ($errors->has('provider'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('provider') }}</strong>
                                </span>
                            @endif
						</div>

						<div class="form-group">
							<input type="submit" class="form-control btn btn-primary" value="Create">
						</div>
					</form>
					
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-md-offset-10 col-md-offset-1 -->
	</div>
	<!-- /.row -->
</div>
<!-- /.container -->

@endsection

@section('footer')

@if(Session::has('error'))
<script type="text/javascript">
    
    swal("Uh oh!", "{{ Session::get('error') }}", "error");

</script>
@endif

@if(Session::has('success'))
<script type="text/javascript">
    
    swal("Good job!", "{{ Session::get('success') }}", "success");

</script>
@endif
@endsection