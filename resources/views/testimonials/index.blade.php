@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Add Testimonial</div>

                <div class="panel-body">

                    @foreach($testimonials->chunk(3) as $testimonialRow)
                        
                        <div class="row">
                            @foreach($testimonialRow as $testimonial)
                                <div class="col-md-4">

                                    <div class="panel panel-default">
                                        <div class="panel-body">
                                            <p>From: {{ $testimonial->contact->first_name . ' ' . $testimonial->contact->last_name }}</p>
                                            <p>Email: {{ $testimonial->contact->email }}</p>
                                            <p>Rating: {{ $testimonial->rating }}</p>
                                            <p>Body: {{ $testimonial->body }}</p>   

                                            <p>
                                                @if(!is_null($testimonial->approved_at))
                                                    <a href="#" class="btn btn-small btn-success disabled">Approved</a>
                                                @else
                                                    <a href="#" class="btn btn-small btn-primary">Approve</a>
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

@endsection
