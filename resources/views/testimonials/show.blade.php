@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Testimonial
                </div>

                <div class="panel-body">

                    <div class="text-center">
                        <h1 style="font-size: 120px;">{{ $testimonial->rating }}</h1>
                        <p><em>out of 5 (rating)</em></p>
                    </div>
                    

                    <hr />

                    <p>{{ $testimonial->body }}</p>

                    <p>From: <strong>{{ $testimonial->contact()->first()->first_name }}</strong> <span class="pull-right">{{ $testimonial->created_at->diffForHumans() }}</span></p>
                    
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

</script>

@endsection
