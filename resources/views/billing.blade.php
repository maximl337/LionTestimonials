@extends('layouts.app')

@section('content')


<div class="panel panel-default">
	<div class="panel-heading">
		Subscribe
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">

		@if(! Auth::user()->subscribed('primary'))
		<form action="" method="POST">
			
			{!! csrf_field() !!}
			<script
			src="https://checkout.stripe.com/checkout.js" class="stripe-button"
			data-key="pk_test_CDnsf6z0xNM52gBCAIL5TYRl"
			data-amount="999"
			data-name="Sell with reviews"
			data-description="Montly Payment"
			data-email="{{ Auth::user()->email }}"
			data-image="//i.imgur.com/rbcf0Fc.png"
			data-locale="auto"
			data-zip-code="true"
			data-label="Subscribe"
			data-currency="usd">
		</script>
	</form>

	@elseif(Auth::user()->subscribed('primary') && ! Auth::user()->subscription('primary')->onGracePeriod())

	<div class="row">
		<div class="col-md-6">

			<form id="resume_subscription" method="POST" action="{{ url('subscription/cancel') }}" role="form">
				{!! csrf_field() !!}

				<div class="form-group">
					<input class="form-control btn btn-danger" type="submit" value="Cancel subscription" />
				</div>
				
			</form>

		</div>
		<!-- /.col-md-6 -->
	</div>
	<!-- /.row -->

	@elseif (Auth::user()->subscription('primary')->onGracePeriod()) 

	<div class="row">
		<div class="col-md-6">
			
			<form id="resume_subscription" method="POST" action="{{ url('subscription/resume') }}" role="form">
				{!! csrf_field() !!}

				<div class="form-group">
					<input class="form-control btn btn-success" type="submit" value="Resume subscription" />
				</div>
				
			</form>

		</div>
		<!-- /.col-md-6 -->
	</div>
	<!-- /.row -->
	
	
	
	@endif


</div>
<!-- /.panel-body -->
</div>

@endsection