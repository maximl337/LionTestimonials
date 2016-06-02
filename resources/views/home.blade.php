@extends('layouts.app')

@section('content')
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
