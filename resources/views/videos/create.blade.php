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
				<div class="panel-heading">
					Create Video
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					
					<form id="create-video" method="POST" action="{{ url('videos') }}" role="form">

						{!! csrf_field() !!}

						<div class="form-group">
							<label for="title">Title</label>
							<input id="title" class="form-control" type="text" name="title" placeholder="Enter a title" required />
						</div>
						
						<input type="hidden" name="token" />

						<input type="hidden" name="thumbnail" />

						<input type="hidden" name="url" />

						<div class="form-group">
							
							<ziggeo ziggeo-limit=15
					        		ziggeo-width=320
					        		ziggeo-height=240
					        		ziggeo-responsive=true
					        		ziggeo-form_accept="#create-video">
							</ziggeo>

						</div>

						<div class="form-group">
							<input type="submit" value="Save" class="form-control btn btn-primary" disabled="disabled">
						</div>
					</form>
					

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel panel-default -->
		</div>
		<!-- /.col-md-10 col-md-offset-1 -->
	</div>
	<!-- /.row -->
</div>
<!-- /.container -->

@endsection

@section('footer')
<script>
		storage = {};

		storage.token = "";

        ZiggeoApi.Events.on("submitted", function (data) {
            //alert("The video with token " + data.video.token + " has been submitted!");
            storage.token = data.video.token;

            var thumbnail = ZiggeoApi.Videos.image(data.video.token);

            var url = ZiggeoApi.Videos.source(data.video.token);

            $('form#create-video  input[name="token"]').val(data.video.token);
			
			console.log(thumbnail);           
            $('form#create-video  input[name="thumbnail"]').val(thumbnail);

            console.log(url);
            $('form#create-video  input[name="url"]').val(url);

            $('form#create-video  input[type="submit"]').removeAttr("disabled");
            

        });

        ZiggeoApi.Events.on("error_recorder", function (data, error) {
			// Triggered when the video recorder encounters an error 
			swal("Uh oh!", "The video recorder encountered an error. We will look into it. Reload the page and try again", "error");
			console.log(error);
		});

		ZiggeoApi.Events.on("access_forbidden", function (data, error) {
			// Triggered when the user does not grant access to the camera
			swal("Uh oh!", "The recorder needs to access your camera to proceed", "error");
		});

</script>
@endsection