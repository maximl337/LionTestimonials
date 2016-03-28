@extends('layouts.app')

@section('head')
    <style type="text/css">
        video {
            max-width: 100%;
            max-height: 400px;
        }
    </style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
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

                    @if(!empty($testimonial->video)) 
                    
                    <video controls>
                        <source type="{{ $testimonial->video_type }}" src="{{ $testimonial->video_src }}"> 
                    </video>

                    @endif
                       
                    <p>{{ $testimonial->body }}</p>

                    <p>From: <strong>{{ $testimonial->contact()->first()->first_name }}</strong> <span class="pull-right">{{ $testimonial->created_at->diffForHumans() }}</span></p>

                    <p>
                        
                        @if(is_null($testimonial->approved_at))
                            <a href="#" data-id="{{ $testimonial->id }}" class="approve btn btn-small btn-primary">Approve</a>
                        @endif
                        
                    </p>
                    
                </div> <!-- .panel-body -->

            </div>
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
            url : "testimonials/approve",
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

    });
});
</script>
@endsection
