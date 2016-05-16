@extends('layouts.app')

@section('head')

<link rel="stylesheet" href="//assets-cdn.ziggeo.com/v1-stable/ziggeo.css" />
<script src="//assets-cdn.ziggeo.com/v1-stable/ziggeo.js"></script>
<script>ZiggeoApi.token = "{{ env('ZIGGEO_APPLICATION_TOKEN') }}";</script>

@endsection

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			
			<div class="panel panel-default">
				<div class="panel-heading">Test video</div>
				<div class="panel-body">

					<ziggeo ziggeo-limit=15
					        ziggeo-width=320
					        ziggeo-height=240>
					</ziggeo>
					
				</div> <!-- .panel-body -->
			</div>
		</div>
	</div>
</div>

@endsection

@section('footer')
<script>
        ZiggeoApi.Events.on("submitted", function (data) {
            alert("The video with token " + data.video.token + " has been submitted!");
        });
</script>
@endsection