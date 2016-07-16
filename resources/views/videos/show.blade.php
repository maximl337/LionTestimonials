@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="//assets-cdn.ziggeo.com/v1-stable/ziggeo.css" />
<script src="//assets-cdn.ziggeo.com/v1-stable/ziggeo.js"></script>
<script>ZiggeoApi.token = "{{ env('ZIGGEO_APPLICATION_TOKEN') }}";</script>
@endsection

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		<h3>{{ $video->title }}</h3>
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div>
					<ziggeo ziggeo-video='{{ $video->token }}'
						ziggeo-width=320
          				ziggeo-height=240
						ziggeo-responsive=true>
					</ziggeo>
				</div>
				
			</div>
			<!-- /.col-md-10 col-md-offset-1 -->
		</div>
		<!-- /.row -->
		
	</div>
	<!-- /.panel-body -->
</div>
@endsection

@section('footer')

<script>	
</script>
	
@endsection