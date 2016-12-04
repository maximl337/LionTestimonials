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
                    <a href="{{ url('testimonials/external') }}" class="btn btn-primary"> External reviews: {{ $testimonials_by_providers['yelp'] + $testimonials_by_providers['google'] }}</a>
                </p>
            </div>
        </div>
        
       

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

                            @if(!empty($testimonial->token))

                            <p>
                                <span class="label label-success">Has video</span>
                            </p>


                            @endif

                            <p>
                                @if(!is_null($testimonial->approved_at))
                                <a href="#" class="btn btn-small btn-success disabled">Approved</a>
                                <a href="#" data-id="{{ $testimonial->id }}" class="remove btn btn-small btn-danger">Delete</a>
                                @else
                                <a href="#" data-id="{{ $testimonial->id }}" class="approve btn btn-small btn-primary">Approve</a>
                                <a href="#" data-id="{{ $testimonial->id }}" class="remove btn btn-small btn-danger">Disapprove</a>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
@if(!empty($average_rating) && !empty($testimonials_by_providers))
<script type="text/javascript">
(function() {

    $('#average_rating_stars').barrating({
        theme: 'bootstrap-stars',
        readonly: true
    });

    var ctx = document.getElementById("chartTwo").getContext('2d');

    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ["Google", "Yelp", "Sell with reviews"],
        datasets: [{
          backgroundColor: [
            "#3498db",
            "#e74c3c",
            "#34495e"
          ],
          data: [
            parseInt("{{ $testimonials_by_providers['google'] }}"), 
            parseInt("{{ $testimonials_by_providers['yelp'] }}"), 
            parseInt("{{ $testimonials_by_providers['local'] }}")
          ]
        }]
      }
    });

})();
    

</script>
@endif

@endsection
