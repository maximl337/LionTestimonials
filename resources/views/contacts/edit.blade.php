@extends('layouts.app')

@section('content')

<div class="panel panel-default">

  <div class="panel-heading">Update</div>

  <div class="panel-body">
    
    <form id="create_contact_form" method="POST" action="{{ url('contacts/' . $contact->id) }}" role="form">

      {!! csrf_field() !!}

      <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
        <label for="first_name">First Name</label>
        <input id="first_name" class="form-control " type="text" name="first_name" value="{{ $contact->first_name }}" />
        @if ($errors->has('first_name'))
        <span class="help-block">
          <strong>{{ $errors->first('first_name') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
        <label for="last_name">Last Name</label>
        <input id="last_name" class="form-control " type="text" name="last_name" value="{{ $contact->last_name }}" />
        @if ($errors->has('last_name'))
        <span class="help-block">
          <strong>{{ $errors->first('last_name') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email">Email</label>
        <input id="email" class="form-control " type="text" name="email" value="{{ $contact->email }}" />
        @if ($errors->has('email'))
        <span class="help-block">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        <label for="phone">Phone</label>
        <input id="phone" class="form-control " type="text" name="phone" value="{{ $contact->phone }}" />
        @if ($errors->has('phone'))
        <span class="help-block">
          <strong>{{ $errors->first('phone') }}</strong>
        </span>
        @endif
      </div>
      
      <div class="form-group">
        <input type="submit" value="Create" class="form-control btn btn-primary">
      </div>
      
    </form>
    
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
