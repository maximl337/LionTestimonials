@extends('layouts.app')

@section('head')

<style type="text/css">
	.panel-body .video {
		width: 100%;

	}
</style>

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
					Videos
					<a href="{{ url('videos/create') }}" class="pull-right">Create</a>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					
					@foreach($videos as $video)
						<div data-id="{{ $video->id }}" class="row video-wrap">
							<div class="col-md-6 col-md-offset-3">
								
								<div class="video">
									<h4>
										{{ $video->title }}
										<a title="Delete video" href="{{ url('videos/' . $video->id) }}" class="delete-video pull-right"><i class="fa fa-times"></i></a>
									</h4>
									
									<div>

										<ziggeo ziggeo-video='{{ $video->token }}'
										        ziggeo-width=320
										        ziggeo-height=240
										        ziggeo-responsive=true>
										</ziggeo>

									</div>
								</div>
								<!-- /.video -->

							</div>
							<!-- /.col-md-6 col-md-offset-3 -->
						</div>
						<!-- /.row -->
						
						

					@endforeach
					
					
					{!! $videos->render() !!}	
					
					
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
<script type="text/javascript">
$(function() {

	$(document).on("click", "a.delete-video", function(e) {
		e.preventDefault();

		var $this = $(this);

		var href = $this.attr("href");

		$.ajax({
			url: href,
			type: "DELETE",
			data: {
				_token: "{{ csrf_token() }}"
			},
			success: function(data) {
				$this.parents(".video-wrap").remove();
				swal("Good job!", "Video deleted successfully", "success");
				console.log(data);
			},
			error: function(xhr, err, respText) {
				swal("Uh oh!", respText, "error");
				console.log(err);
			}

		});
	}); // on click

});
</script>
@endsection