@extends('layouts.app')


@section('head')
<style type="text/css">
    
    .avatar {
        width: 200px;
        margin: auto auto;
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
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
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
                        <div class="col-md-4 col-md-offset-4">
                            <div class="avatar">
                                <img src="{{ $user->picture }}">
                            </div>
                            
                            <div class="name">
                                <p>{{ $user->getName() }}</p>
                            </div>
                            
                        </div>
                    </div>

                        
                        <div class="row">
                            @foreach($testimonials as $testimonial)
                                <div class="col-md-12">

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
                                        <p>From: <strong>{{ $testimonial->contact()->first()->first_name }}</strong> {{ $testimonial->created_at->diffForHumans() }}</p>
                                        <hr />
                                    </section>
                                    
                                </div> <!-- .col-md-4 -->
                            @endforeach

                            <div class="col-md-12">
                            {!! $testimonials->render() !!}
                            </div>
                        </div><!-- .row -->

                  

                    @if(!empty($user->business_name) ||
                        !empty($user->business_logo))

                    <hr />

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
            
                    <div class="row {{ Auth::check() ? '' : ' hidden' }}">
                        <div class="col-md-10 col-offset-md-1">
                            <h5>Copy the code below and add it to any website to show your approved testimonials</h5>
                            <pre style="padding-left: 0px;">
                                &lt;iframe style="width: 100%;" src="{{ url('users' . '/' . $user->id . '/testimonials/public') }}"&gt;&lt;/iframe&gt;
                            </pre>
                        </div>
                    </div>
                    
                </div> <!-- .panel-body -->
            </div>
        </div>
    </div>
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

