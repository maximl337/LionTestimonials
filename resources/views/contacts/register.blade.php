@extends('layouts.public')

@section('head')

@if(!empty($branding))

<style type="text/css">
	

</style>

@endif

@endsection

@section('content')

<div class="panel panel-default">
	
	<div class="panel-heading branding-primary-color">
        Register
    </div>

    <div class="panel-body">

    	@if($user)
			
			@include('contacts.partials._register_form', ['primary_color' => $branding->primary_color])

    	@endif
    	
    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel panel-default -->

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