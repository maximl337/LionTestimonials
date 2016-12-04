@extends('layouts.app')

@section('head')

<style type="text/css">
	.business-list {
		max-height: 200px;
		overflow-x: auto;
		z-index: 999;
	}
</style>
@endsection

@section('content')

<div class="panel panel-default">
	<div class="panel-heading">
		
		Add Third Party Testimonial Sites Links
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">

		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#find" aria-controls="home" role="tab" data-toggle="tab">Find</a></li>
			<li role="presentation"><a href="#add" aria-controls="profile" role="tab" data-toggle="tab">Add Manually</a></li>
		</ul>

		<!-- Tab panes -->
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="find">

				<form id="search_external_review_sites" method="POST" action="{{ url('externalLinks') }}" role="form">
					
					{!! csrf_field() !!}


					<div class="form-group">
						<label for="provider">Select a provider</label>
						<select id="provider" class="form-control" name="provider">
							<option value="yelp">Yelp</option>
							<option value="google">Google</option>
						</select>
					</div>

					<div class="form-group">
						<label for="location">Business location</label>
						<input id="location" class="form-control " type="text" name="location" value="" placeholder="Enter a combination of address, neighborhood, city, state or zip, optional country" required="required" />
					</div>

					<div class="form-group">
						<label for="query_string">Business name</label>
						<input id="query_string" class="form-control " type="search" name="query_string" value="" placeholder="Enter business name" autocomplete="off" />
						<div class="businesses form-control collapse">
							
						</div>
					</div>

					<input id="business_id" type="hidden" name="business_id">

					<input id="business_name" type="hidden" name="business_name">

					<input id="business_url" type="hidden" name="business_url">

					<div class="form-group">
						<input type="submit" class="form-control btn btn-primary" value="Save" />
					</div>
					
				</form>
				
				
			</div>
			<!-- /#find -->

			<div role="tabpanel" class="tab-pane" id="add">

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
			<!-- /#add -->
		</div>
		
		
		
	</div>
	<!-- /.panel-body -->
</div>

@endsection

@section('footer')

<script type="text/javascript">

$(function() {

	var timer = null;

	$(document).on('keyup', 'form#search_external_review_sites #query_string', function(e) {

		e.preventDefault();

		var $this = $(this);

		var $wrap = $(".businesses");

		if($this.val().length < 3) {
			return false;
		}

		if (timer) {
	        clearTimeout(timer);
	    }

		$wrap.show();

		$wrap.html('<p class="text-center"><i class="fa fa-gear fa-spin"></i> Searching<p>');

		var form = $this.closest('form');

		var action = "{{ url('externalReviewSites/search') }}";

		timer = setTimeout(function() {

			$.ajax({
				url: action,
				type: 'POST',
				data: form.serialize(),
				success: function(data) {
					$wrap.html(data);
				}, 
				error: function(jqXHR) {

					var resp = JSON.parse(jqXHR.responseText);
					var err = "";

					$.each(resp, function(i, v) {
						err += " " + v;
					});

					$wrap.html("").hide();

					swal("Uh oh", err, "error");
				}
			});
		}, 500);
		

	}); // EO search

	$(document).on('click', '.business-item', function(e) {

		e.preventDefault();

		var $this = $(this);

		var form = $this.closest("form");

		var $wrap = $this.closest(".businesses");

		var business_id = $this.attr("data-id");

		var business_name = $this.attr("data-name");

		var business_url = $this.attr("data-url");

		form.find("#business_id").val(business_id);

		form.find("#business_name").val(business_name);

		form.find("#business_url").val(business_url);

		form.find("#query_string").val(business_name);

		// close the wrap
		$wrap.html("").hide();
	});
	// EO select business

	// $(document).on("submit", "form#search_external_review_sites", function(e) {
		
	// 	e.preventDefault();

	// 	var $form = $(this);

	// 	var $btn = $form.find('input[type="submit"]');

	// 	console.log($form.serializeArray());

	// 	$btn.val('Saving ...').attr('disabled', 'disabled');

	// });

}); // EO DOM READY

</script>

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