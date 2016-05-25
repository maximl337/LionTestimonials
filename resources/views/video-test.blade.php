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

			var video = document.querySelector('video');
			var streamRecorder;
			var webcamstream;

            navigator.getUserMedia = ( navigator.getUserMedia ||
                       navigator.webkitGetUserMedia ||
                       navigator.mozGetUserMedia ||
                       navigator.msGetUserMedia)

            if (navigator.getUserMedia) {
               	
               	var recorder;

				function successCallback(audioVideoStream) {
				    recorder = RecordRTC(audioVideoStream, {
				        type: 'video',
				        mimeType: 'video/webm',
				        bitsPerSecond: 512 * 8 * 1024
				    });
				    recorder.startRecording();
				}

				function errorCallback(error) {
				    console.error(error);
				}

				var mediaConstraints = {
				    video: true,
				    audio: true
				};

				navigator.mediaDevices.getUserMedia(mediaConstraints).then(successCallback).catch(errorCallback);
               
				document.querySelector('#btn-stop-recording').onclick = function() {
					if (!recorder) return;
					recorder.stopRecording(function() {
						var audioVideoBlob = recorder.blob;

						// you can upload Blob to PHP/ASPNET server
						//uploadBlob(audioVideoBlob);

						// you can even upload DataURL
						// recorder.getDataURL(function(dataURL) {
						// 	$.ajax({
						// 		type: 'POST',
						// 		url: '/save.php',
						// 		data: {
						// 			dataURL: dataURL
						// 		},
						// 		contentType: 'application/json; charset=utf-8',
						// 		success: function(msg) {
						// 			alert('Successfully uploaded.');
						// 		},
						// 		error: function(jqXHR, textStatus, errorMessage) {
						// 			alert('Error:' + JSON.stringify(errorMessage));
						// 		}
						// 	});
						// });
					});
				};
               	

            } else {
               console.log('getUserMedia not supported on your browser!');
            }



        </script>

@endsection