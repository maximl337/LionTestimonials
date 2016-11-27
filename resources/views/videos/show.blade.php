@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="//assets-cdn.ziggeo.com/v1-stable/ziggeo.css" />
<script src="//assets-cdn.ziggeo.com/v1-stable/ziggeo.js"></script>
<script>ZiggeoApi.token = "{{ env('ZIGGEO_APPLICATION_TOKEN') }}";</script>
@endsection

@section('content')
<div class="panel panel-default">
	<div class="panel-heading branding-primary-color">
		<h3>{{ $video->title }}</h3>
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">

		<div class="row">
			<div class="col-md-12">
				<ul class="list-inline pull-right">
	                <li>
	                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('users/'.$user->id.'/public') }}" class="btn btn-default btn-facebook popup">
	                    <i class="fa fa-facebook"></i>
	                    <span class="text">Share</span>
	                    </a>
	                </li>
	                <li>
	                    <a href="http://twitter.com/home?status={{ url('users/'.$user->id.'/public') }}"  class="btn btn-default btn-twitter popup">
	                    <i class="fa fa-twitter"></i>
	                    <span class="text">Share</span>
	                    </a>
	                </li>
	                <li>
	                    <a href="https://plus.google.com/share?url={{ url('users/'.$user->id.'/public') }}"  class="btn btn-default btn-googleplus popup">
	                    <i class="fa fa-google-plus"></i>
	                    <span class="text">Share</span>
	                    </a>
	                </li>
	            </ul>
			</div>
		</div>


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
  
  $('#user-profile .container').css("background-color", "{{ $branding->background_color }}")

</script>

@endif

@endsection