@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			
			<div class="panel panel-default">
				<div class="panel-heading">Test video</div>
				<div class="panel-body">

					<video autoplay muted controls></video>

					<p>
						<button class="start" id="recorder">record</button>
						<span class="state"></span>
					</p>
					
				</div> <!-- .panel-body -->
			</div>
		</div>
	</div>
</div>

@endsection

@section('footer')

<script type="text/javascript">

			$(document).on("click", "button.start", function(e) {

				var $this = $(this);

				$this.text('stop').removeClass("start").addClass("stop");
				
				$(".state").text("...recording");

				startRecording();

			});

			$(document).on("click", "button.stop", function(e) {

				var $this = $(this);

				$this.text('start').removeClass("stop").addClass("start");
				
				$(".state").text("");

				stopRecording();

			});

			var video = document.querySelector('video');
			var streamRecorder;
			var webcamstream;

            navigator.getUserMedia = ( navigator.getUserMedia ||
                       navigator.webkitGetUserMedia ||
                       navigator.mozGetUserMedia ||
                       navigator.msGetUserMedia)

            if (navigator.getUserMedia) {
               console.log('getUserMedia supported.');

                 var errorCallback = function(e) {
				    console.log('Reeeejected!', e);
				  };

				  // Not showing vendor prefixes.
				  navigator.getUserMedia({video: true, audio: true}, function(localMediaStream) {
				    video.src = window.URL.createObjectURL(localMediaStream);

				    webcamstream = localMediaStream;

				    // Note: onloadedmetadata doesn't fire in Chrome when using it with getUserMedia.
				    // See crbug.com/110938.
				    video.onloadedmetadata = function(e) {
				      // Ready to go. Do some stuff.
				    };
				  }, errorCallback);


				  function startRecording() {
					    navigator.getUserMedia({video: true, audio: true}, function(localMediaStream) {
					    video.src = window.URL.createObjectURL(localMediaStream);

						    streamRecorder = localMediaStream.record();

						    // Note: onloadedmetadata doesn't fire in Chrome when using it with getUserMedia.
						    // See crbug.com/110938.
						    video.onloadedmetadata = function(e) {
						      // Ready to go. Do some stuff.
						    };
					  }, errorCallback);
					}

					function stopRecording() {
					    streamRecorder.getRecordedData(postVideoToServer);
					}
               
               function postVideoToServer(videoblob) {

				    // var data = {};
				    // data.video = videoblob;
				    // data.metadata = 'test metadata';
				    // data.action = "upload_video";
				    // jQuery.post("http://www.foundthru.co.uk/uploadvideo.php", data, onUploadSuccess);
				    video.src = window.URL.createObjectURL(videoblob);
				}
				function onUploadSuccess() {
				    alert ('video uploaded');
				}
            } else {
               console.log('getUserMedia not supported on your browser!');
            }



        </script>

@endsection