@extends('layouts.app')

@section('head')

<style>
@media screen and (min-width: 320px) {

   .rating { 
      font-size: 50px; 
    }

}
@media screen and (min-width: 680px) {

   .rating { 
      font-size: 70px; 
    }

}
@media screen and (min-width: 1224px) {

   .rating { 
      font-size: 100px; 
    }

}
@media screen and (min-width: 1400px) {

   .rating { 
      font-size: 120px; 
    }

}
</style>

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
    
    <div class="row">
        <div class="col-md-12">
            @if(!is_null($testimonial->approved_at))
              <div class="pull-right"><span class="label label-success"></span></div>
            @endif
            <div class="text-center">
              <h1 class="rating">{{ $testimonial->rating }}</h1>
              <p><em>out of 5 (rating)</em></p>
            </div>
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
   

    @if(!empty($testimonial->token)) 
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div>
                <ziggeo ziggeo-video='{{ $testimonial->token }}'
                    ziggeo-responsive=true>
                </ziggeo>
            </div>
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.row -->
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

        <div class="categories">
        @if($testimonial->categories()->where('testimonial_id', $testimonial->id)->exists())
            <em>Category: </em>
            <strong>{{ $testimonial->categories()->where('testimonial_id', $testimonial->id)->first()->name }}</strong>
            @if(Auth::check())
                <a href="#" data-toggle="modal" data-target=".bs-example-modal-lg">Change Category</a>
            @endif
        @else
            @if(Auth::check())
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example-modal-lg">Add Category</button>
            @endif
        @endif
        </div>

        <p>From: <strong>{{ $testimonial->contact()->first()->first_name }}</strong> <em>{{ $testimonial->created_at->diffForHumans() }}</em></p>

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

<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Category</h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-default">
                    
                    <div class="panel-body">
                        
                        <form id="add_category" method="POST" action="{{ url('categories') }}" role="form">
                            
                            {!! csrf_field() !!}

                            <input type="hidden" name="testimonial_id" value="{{ $testimonial->id }}">
                            <div class="form-group">
                                <label for="">Add New Category</label>
                                <input class="form-control" type="text" name="category_name" placeholder="Add a category name" />
                            </div>

                            @if(Auth::user()->categories()->exists())
                                <div class="form-group">
                                    <label for="">Or select and existing category</label>
                                    <select name="category_id" class="form-control">
                                        @foreach(Auth::user()->categories()->get() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            

                            <div class="form-group">
                                <input id="" class="form-control btn btn-primary" type="submit" value="Add" />
                            </div>
                            
                        </form>
                        

                    </div> <!-- /.panel-body -->
                </div>
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
