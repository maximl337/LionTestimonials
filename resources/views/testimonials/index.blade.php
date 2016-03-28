@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    You Testimonials
                
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

                    @foreach($testimonials->chunk(3) as $testimonialRow)
                        
                        <div class="row">
                            @foreach($testimonialRow as $testimonial)
                                <div class="col-md-4">

                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <p class="pull-right"><a href="testimonials/{{ $testimonial->id }}">view</a></p>
                                            <p>From: {{ $testimonial->contact->first_name . ' ' . $testimonial->contact->last_name }}</p>
                                            <p>Email: {{ $testimonial->contact->email }}</p>
                                            <p>Rating: {{ $testimonial->rating }}</p>
                                            <p>Body: {{ str_limit($testimonial->body, 20) }}</p>   
                                                
                                            @if(!is_null($testimonial->video))

                                                <p>
                                                    <span class="label label-success">Has video</span>
                                                </p>
                                                

                                            @endif

                                            <p>
                                                @if(!is_null($testimonial->approved_at))
                                                    <a href="#" class="btn btn-small btn-success disabled">Approved</a>
                                                @else
                                                    <a href="#" data-id="{{ $testimonial->id }}" class="approve btn btn-small btn-primary">Approve</a>
                                                @endif
                                            </p>     
                                        </div> <!-- .panel-body -->
                                    </div> <!-- .panel -->
                                    
                                </div> <!-- .col-md-4 -->
                            @endforeach
                        </div><!-- .row -->

                    @endforeach
                    
                    {!! $testimonials->render() !!}
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
            url : "{{ env('APP_URL') }}testimonials/approve",
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
