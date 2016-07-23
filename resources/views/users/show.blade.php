@extends('layouts.app')


@section('head')

<meta property="og:url"           content="{{ url('users/'.$user->id.'/public') }}" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="Sell with reviews: {{ $user->getName() }}" />
<meta property="og:description"   content="Convert customers to your brand champions" />
<meta property="og:image"         content="{{ $user->avatar ?: 'http://www.sellwithreviews.com/wp-content/uploads/2016/03/sell-with-reviews-logo-02.png' }}" />


<style type="text/css">

  .avatar {
    width: 180px;
    height: 180px;
    border: 2px solid #DDD;
    margin: 20px auto;
    border-radius: 50%;
    overflow: hidden;
}

.avatar img {
    width: 100%;
}

.name {
    font-size: 1.2em;
    font-weight: bold;
    text-align: center;
    margin-top: 10px;
}

</style>

<link rel="stylesheet" href="//assets-cdn.ziggeo.com/v1-stable/ziggeo.css" />
<script src="//assets-cdn.ziggeo.com/v1-stable/ziggeo.js"></script>
<script>ZiggeoApi.token = "{{ env('ZIGGEO_APPLICATION_TOKEN') }}";</script>
@endsection

@section('content')


<div class="panel panel-default">

    <div class="panel-heading">
        Profile 
        @if(!is_null($user->verified_at))
            <span class="label label-success">Verified</span>
        @endif

        @if(Auth::check())
            <a href="{{ url('profile/edit') }}" class="pull-right"><i class="fa fa-pencil"></i> Edit</a>
        @endif

            
    </div>

    <div class="panel-body">  

        <div class="row">
            <div class="col-md-12">
                
            <ul class="list-inline pull-right">
                <li>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('users/'.$user->id.'/public') }}" class="btn btn-default btn-facebook popup">
                    <i class="fa fa-facebook"></i>
                    <span class="text">Share</span>
                    </a>
                </li>
                <li>
                    <a href="http://twitter.com/home?status={{ url('users/'.$user->id.'/public') }}"  class="btn btn-default btn-twitter popup">
                    <i class="fa fa-twitter"></i>
                    <span class="text">Share</span>
                    </a>
                </li>
                <li>
                    <a href="https://plus.google.com/share?url={{ url('users/'.$user->id.'/public') }}"  class="btn btn-default btn-googleplus popup">
                    <i class="fa fa-google-plus"></i>
                    <span class="text">Share</span>
                    </a>
                </li>
            </ul>

                


            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->                

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                @if($user->avatar)
                    <div class="avatar">
                        <img src="{{ $user->picture }}">
                    </div>
                @endif
                

                <div class="name">
                    <p>{{ $user->getName() }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                
                @if(!is_null($video))

                    <ziggeo ziggeo-video='{{ $video->token }}'
                            responsive=true>
                    </ziggeo>
                    
                @else

                    <a class="form-control btn btn-primary" href="{{ url('videos/create') }}">Make a profile video</a>
                    
                @endif

            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->

        @if(count($testimonials) > 0)
            <div class="row">

                @foreach($testimonials as $testimonial)
                    <div class="col-md-12">

                        @if(!empty($testimonial))
                            <section>
                                <h2>Rating: {{ $testimonial->rating }}</h2>
                                <p>{{ $testimonial->body }}</p>
                                <p>
                                @if(!empty($testimonial->token)) 
                                    <ziggeo ziggeo-video='{{ $testimonial->token }}'
                                            responsive=true>
                                    </ziggeo>
                                @endif
                                </p>
                                <p>{{ $testimonial->created_at->diffForHumans() }}</p>
                                <hr />
                            </section>
                        @endif

                    </div> <!-- .col-md-12 -->
                @endforeach
                <div class="col-md-12">
                    {!! $testimonials->render() !!}
                </div>
            </div><!-- .row -->
        @endif


       

        <hr />

<div class="row">
    <div class="col-md-12">
        
         @if(!empty($user->business_name) ||
            !empty($user->business_logo))

            <div class="business">

  @if(!empty($user->business_logo))
  <div class="business_logo">
    <img src="{{ $user->business_logo }}">
</div>
@endif

@if(!empty($user->business_name))

<div class="business_name">
    <h4>{{ $user->business_name }}</h4>
</div>

@endif
</div>

@endif

@if(!empty($user->bio))

<hr />

<div class="about">
  <p>{{ $user->bio }}</p>
</div>

@if(!empty($user->getAddress()))

<div class="address">
  <p>{{ $user->getAddress() }}</p>
</div>
@endif

@endif

    </div>
    <!-- /.col-md-12 -->
</div>
<!-- /.row -->





<div class="row {{ Auth::check() ? '' : ' hidden' }}">
  <div class="col-md-12">
    <h6>Copy the code below and add it to any website to show your approved testimonials</h6>
    <pre>&lt;iframe style="width: 100%;" src="{{ url('users' . '/' . $user->id . '/testimonials/public') }}"&gt;&lt;/iframe&gt;</pre>
</div>
</div>

</div> <!-- .panel-body -->
</div>
@endsection

@section('footer')

@if(Session::has('success'))
<script type="text/javascript">

  swal("Good job!", "{{ Session::get('success') }}", "success");

    //console.log('has message');

</script>
@endif

@if(Session::has('error'))
<script type="text/javascript">

    swal("Uh oh!", "{{ Session::get('error') }}", "error");

    //console.log('has message');

</script>
@endif
@endsection

