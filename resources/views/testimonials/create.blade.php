@extends('layouts.public')

@section('head')

<link rel="stylesheet" href="//assets-cdn.ziggeo.com/v1-stable/ziggeo.css" />
<script src="//assets-cdn.ziggeo.com/v1-stable/ziggeo.js"></script>
<script>ZiggeoApi.token = "{{ env('ZIGGEO_APPLICATION_TOKEN') }}";</script>

@endsection

@section('content')
  @include('testimonials._partials.create-testimonial')
@endsection


@section('footer')

@if(!empty($data['branding']->primary_color) && !empty($data['branding']->text_color))

<script type="text/javascript">
  $(".branding-primary-color")
            .css("color", "{{ $data['branding']->text_color }}");        

    $(".branding-primary-color")
        .css("background-color", "{{ $data['branding']->primary_color }}");
</script>

@endif

@if(!empty($data['branding']->background_color))

<script type="text/javascript">
  
  $('body > .container').css("background-color", "{{ $data['branding']->background_color }}")

</script>

@endif

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
    // form submit
    $("form#create-testimonial").on("submit", function(e) {
      e.preventDefault(); 
      var email = $(this).find('input#email').val();
      var rating = $(this).find('select#rating').val();

      if(email === undefined || email === null || email.length == 0) {
        swal("Uh oh!", "Please enter an email", "error");   
        return false;
      }
      if(rating === undefined || rating === null || rating.length == 0) {
        swal("Uh oh!", "Please add a rating", "error"); 
        return false;
      }
      $('#create-testimonial')[0].submit();
    });


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