@extends('layouts.public')

@section('head')
<link rel="stylesheet" href="//assets-cdn.ziggeo.com/v1-stable/ziggeo.css" />
<script src="//assets-cdn.ziggeo.com/v1-stable/ziggeo.js"></script>
<script>ZiggeoApi.token = "{{ env('ZIGGEO_APPLICATION_TOKEN') }}";</script>
@endsection

@section('content')
<div class="panel panel-default">
	<div class="panel-heading branding-primary-color">
		Thankyou
	</div>
	
	<div class="panel-body">
		
		<div class="row">
			<div class="col-md-12">
				
				<!-- Show thank you video -->
				@if($video)

					<div>
						<ziggeo ziggeo-video='{{ $video->token }}'
							ziggeo-width=320
	          				ziggeo-height=240
	          				autoplay=true>
						</ziggeo>
					</div>

				@endif
				

				<!-- Show external profiles as buttons-->
				@if($external_sites)
					<div class="row">
						@foreach($external_sites as $site)

							<div class="col-md-3">
								<a href="{!! $site->url !!}" class="btn btn-default external {{ $site->provider }}">
									{{ $site->provider }}
								</a>	
							</div>

						@endforeach
					</div>
					<!-- /.row -->
				@endif

				<h3>Thank you for the feedback</h3>
				
			</div>
			<!-- /.col-md-12 -->
		</div>
		<!-- /.row -->

	</div>
	
</div>
@endsection

@section('footer')

@if(!empty($branding->primary_color) && !empty($branding->text_color))

<script type="text/javascript">
  $(".branding-primary-color")
            .css("color", "{{ $branding->text_color }}");        

    $(".branding-primary-color")
        .css("background-color", "{{ $branding->primary_color }}");
</script>

@endif

@if(!empty($branding->background_color))

<script type="text/javascript">
  
  $('body > .container').css("background-color", "{{ $branding->background_color }}")

</script>

@endif

@endsection