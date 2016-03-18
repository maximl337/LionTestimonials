@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Profile</div>

                <div class="panel-body">
                    <form id="edit-profile" method="post" action="{{ url('/user') }}" role="form">

                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="first_name">First Name</label>
                            <input id="first_name" class="form-control " type="text" name="first_name" value="{{ $user->first_name ?: old('first_name') }}" />
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input id="last_name" class="form-control " type="text" name="last_name" value="{{ $user->last_name }}" />
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Update" class="form-control btn btn-primary" />
                        </div>
                        
                    </form>
                    
                </div> <!-- .panel-body -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')

@if(Session::has('message'))
<script type="text/javascript">
    
    swal("Good job!", "Your profile was updated", "success")


</script>
@endif
@endsection
