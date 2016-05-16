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
            
            @include('testimonials._partials.create-testimonial')
        </div>
    </div>
</div>
@endsection


@section('footer')

@if(Session::has('error'))
<script type="text/javascript">
    
    swal("Uh oh!", "{{ Session::get('error') }}", "error");


</script>
@endif

@if(Session::has('success'))
<script type="text/javascript">
    
    swal("Good job!", "{{ Session::get('success') }}", "success");


</script>
@endif


<script type="text/javascript">

$(function() { 


    storage = {};

    storage.token = "";

    ZiggeoApi.Events.on("recording", function (data) {
        $('form#create-testimonial input[type="submit"]').attr("disabled", "disabled");
    });

    ZiggeoApi.Events.on("submitted", function (data) {
        //alert("The video with token " + data.video.token + " has been submitted!");
        storage.token = data.video.token;

        var thumbnail = ZiggeoApi.Videos.image(data.video.token);

        var url = ZiggeoApi.Videos.source(data.video.token);
    
        $('form#create-testimonial  input[name="token"]').val(data.video.token);
                
        $('form#create-testimonial  input[name="thumbnail"]').val(thumbnail);

        $('form#create-testimonial  input[name="url"]').val(url);

        $('form#create-testimonial  input[type="submit"]').removeAttr("disabled");
        

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


}); // EO DOM READY
        


</script>

@endsection