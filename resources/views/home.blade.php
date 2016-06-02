@extends('layouts.app')

@section('content')


  <ul class="quick-options row">
  	<li class="col-md-3">
  		<a href="{{ url('contacts') }}"><i class="font-icon ion-email-unread"></i>Contacts</a>
  	</li>
  	<li class="col-md-3">
  		<a href="{{ url('contacts') }}"><i class="font-icon ion-ios-bookmarks-outline"></i>Testimonials</a>
  	</li>
  	<li class="col-md-3">
  		<a href="{{ url('contacts') }}"><i class="font-icon ion-ios-camera-outline"></i>Videos</a>
  	</li>
  	<li class="col-md-3">
  		<a href="{{ url('contacts') }}"><i class="font-icon ion-ios-cog-outline"></i>Profile</a>
  	</li>
  </ul>

  <hr/>
  
  <div class="panel panel-default">
    <div class="panel-heading">Dashboard</div>
    <div class="panel-body">
      <a href="{{ url('contacts') }}" class="btn btn-primary"> Contacts </a>
    </div>
  </div>
@endsection

@section('footer')

@if(Session::has('subscription_cancelled'))
<script type="text/javascript">
  swal("We are sorry to see you go", "{{  Session::get('subscription_cancelled') }}", "info");
</script>
@endif

@endsection
