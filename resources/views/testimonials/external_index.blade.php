@extends('layouts.app')

@section('head')

@endsection

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">
        Your Testimonials
        <div class="btn-group pull-right">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                filter <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="?filter=approved">Approved</a></li>
                <li><a href="?filter=unapproved">Unapproved</a></li>
                <li><a href="?">Clear</a></li>
            </ul>
        </div>
    </div>

    <div class="panel-body">

        @include('testimonials._partials._charts', ['average_rating' => $average_rating, 'review_count' => $testimonials_by_providers])

        <div class="row">
            <hr />
            <div class="col-md-12">
                <p class="pull-right">
                    <a href="{{ url('testimonials') }}" class="btn btn-primary"> Your Testimonials: {{ $testimonials_by_providers['local'] }}</a>
                </p>
            </div>
        </div>
        
       
        <div class="row">
            @foreach($testimonials as $testimonial)        
                
                <div class="col-md-12">

                    {{ str_limit($testimonial->body) }}
                    <hr />
                </div>
                <!-- /.col-md-12 -->
                
            @endforeach
        </div><!-- .row -->

        {!! $testimonials->render() !!}
    </div> <!-- .panel-body -->
</div>

@endsection

@section('footer')

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

    }); // EO approve


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

                $this.remove();

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
