@extends('layouts.app')

@section('head')
<link rel="stylesheet" href="//assets-cdn.ziggeo.com/v1-stable/ziggeo.css" />
<script src="//assets-cdn.ziggeo.com/v1-stable/ziggeo.js"></script>
<script>ZiggeoApi.token = "{{ env('ZIGGEO_APPLICATION_TOKEN') }}";</script>
@endsection
@section('content')

<div class="panel panel-default">
  <div class="panel-heading">
    Testimonial
  </div>

  <div class="panel-body">
    
    @if(!is_null($testimonial->approved_at))
    <div class="pull-right"><span class="label label-success"></span></div>
    @endif
    <div class="text-center">
      <h1 style="font-size: 120px;">{{ $testimonial->rating }}</h1>
      <p><em>out of 5 (rating)</em></p>
    </div>
    

    <hr />

    @if(!empty($testimonial->token)) 
    
    <ziggeo ziggeo-video='{{ $testimonial->token }}'
      responsive=true>
    </ziggeo>
    @endif
    
    <p>{{ $testimonial->body }}</p>

    <p>From: <strong>{{ $testimonial->contact()->first()->first_name }}</strong> <span class="pull-right">{{ $testimonial->created_at->diffForHumans() }}</span></p>

    <p>
      
      @if(is_null($testimonial->approved_at))
      <a href="#" data-id="{{ $testimonial->id }}" class="approve btn btn-small btn-primary">Approve</a>
      <a href="#" data-id="{{ $testimonial->id }}" class="remove btn btn-small btn-danger">Disapprove</a>
      @else
      <a href="#" data-id="{{ $testimonial->id }}" class="remove btn btn-small btn-danger">Delete</a>
      @endif
      
    </p>
    
  </div> <!-- .panel-body -->

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

    $(document).on("click", ".approve", function(e) {
      e.preventDefault();
      $this = $(this);
      $this.addClass('disabled');
      $this.html('<i class="fa fa-cog fa-spin"></i>');
      var sendData = {
        _token: "{{ csrf_token() }}",
        id: $this.data('id'),
      };

      $.ajax({
        type : "POST",
        url : "{{ url('testimonials/approve') }}",
        data : sendData,
            //contentType: "application/json; charset=UTF-8",
            success: function (response) {  
              $this.removeClass('btn-primary').addClass('btn-success').html('Approved');
            },
            statusCode: {
              403: function() {
                swal("Uh oh!", "Forbidden request", "error");
              },
              404: function() {
                swal("Uh oh!", "Could not find the resource", "error");
              },
              500: function() {
                swal("Uh oh!", "Internal server error", "error");
              }
            },
            error: function (e) {
              swal("Uh oh!", e, "error");
            } 
          });

    }); // eo approve

    $(document).on("click", ".remove", function(e) {
      e.preventDefault();

      $this = $(this);

      $this.addClass('disabled');

      $this.html('<i class="fa fa-cog fa-spin"></i>');

      var sendData = {
        _token: "{{ csrf_token() }}",
        id: $this.data('id'),
      };



      $.ajax({
        type : "POST",
        url : "{{ url('testimonials/remove') }}",
        data : sendData,
        success: function (response) {  

          
          swal({
            title: 'Testimonial removed',
            text: "Would you like to send " + response.contact_name + " another request for testimonial",
            type: 'success',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonText: "Not right now",
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }, function(isConfirm) {   
            if (isConfirm) {     
              window.location.href= response.redirect_url;
            } 
          });
          
          
        },
        statusCode: {
          403: function() {
            swal("Uh oh!", "Forbidden request", "error");
          },
          404: function() {
            swal("Uh oh!", "Could not find the resource", "error");
          },
          500: function() {
            swal("Uh oh!", "Internal server error", "error");
          }
        },
        error: function (e) {
          swal("Uh oh!", e, "error");
        } 
      });

    }); // EO remove
  });
</script>
@endsection
