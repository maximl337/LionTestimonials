@inject('countries', 'App\Utilities\Countries')

@inject('states_usa', 'App\Utilities\States\Usa')

@inject('states_canada', 'App\Utilities\States\Canada')

@extends('layouts.app')

@section('head')

<style type="text/css">

  .states {
    display: none;
  }

  .current-picture {
    width: 100px;
    margin: 10px;
  }

  .current-picture img {
    width: 100%;
  }

  .current-business-logo {
    height: 200px;
    margin: 10px;
  }

  .current-business-logo {
    height: 100%;
  }
</style>

@endsection

@section('content')

<div class="panel panel-default">
  <div class="panel-heading">Edit Profile</div>

  <div class="panel-body">
    <form  enctype="multipart/form-data" id="update-profile" method="post" action="{{ url('/user') }}" role="form">

      {!! csrf_field() !!}

      <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
        <label for="first_name">First Name</label>
        <input id="first_name" class="form-control " type="text" name="first_name" value="{{ $user->first_name ?: old('first_name') }}" />
        @if ($errors->has('first_name'))
        <span class="help-block">
          <strong>{{ $errors->first('first_name') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
        <label for="last_name">Last Name</label>
        <input id="last_name" class="form-control " type="text" name="last_name" value="{{ $user->last_name ?: old('last_name') }}" />
        @if ($errors->has('last_name'))
        <span class="help-block">
          <strong>{{ $errors->first('last_name') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group">
        <label for="picture">
          Profile picture
          @if(!empty($user->picture))
          <div class="current-picture">
            <img src="{{ $user->picture }}">
          </div>
          @endif
          <input id="picture" class="form-control " type="file" name="picture" value="" />
        </label>
        
        @if ($errors->has('picture'))
        <span class="help-block">
          <strong>{{ $errors->first('picture') }}</strong>
        </span>
        @endif
      </div>


      <div class="form-group{{ $errors->has('business_name') ? ' has-error' : '' }}">
        <label for="business_name">Business name</label>
        <input id="business_name" class="form-control " type="text" name="business_name" value="{{ $user->business_name ?: old('business_name') }}" />
        @if ($errors->has('business_name'))
        <span class="help-block">
          <strong>{{ $errors->first('business_name') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group">
        <label for="business_logo">
          Business logo
          @if(!empty($user->business_logo))
          <div class="current-business-logo">
            <img src="{{ $user->business_logo }}">
          </div>
          @endif
          <input id="business_logo" class="form-control " type="file" name="business_logo" value="" />
        </label>
        
        @if ($errors->has('business_logo'))
        <span class="help-block">
          <strong>{{ $errors->first('business_logo') }}</strong>
        </span>
        @endif
      </div>

      

      <div class="form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
        <label for="bio">Bio</label>

        <textarea name="bio" class="form-control" rows="10">{{ $user->bio ?: old('bio') }}</textarea>
        @if ($errors->has('bio'))
        <span class="help-block">
          <strong>{{ $errors->first('bio') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
        <label for="street">Street</label>
        <input id="street" class="form-control " type="text" name="street" value="{{ $user->street ?: old('street') }}" />
        @if ($errors->has('street'))
        <span class="help-block">
          <strong>{{ $errors->first('street') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
        <label for="city">City</label>
        <input id="city" class="form-control " type="text" name="city" value="{{ $user->city ?: old('city') }}" />
        @if ($errors->has('city'))
        <span class="help-block">
          <strong>{{ $errors->first('city') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
        <label for="country">Country</label>
        <select class="form-control" name="country" id="country" required>

          @foreach($countries::all() as $country => $code)

          <option value="{{ $code }}" {{ $user->country == $code ? ' selected="selected"' : '' }}>{{ $country }}</option>

          @endforeach

        </select>
        @if ($errors->has('country'))
        <span class="help-block">
          <strong>{{ $errors->first('country') }}</strong>
        </span>
        @endif
      </div>

      <!-- USA states -->
      <div id="us" class="states form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        <label for="state">State</label>
        <select class="form-control" name="state" id="state" required>

          @foreach($states_usa::all() as $code => $state)

          <option value="{{ $code }}" {{ $user->state == $code ? ' selected="selected"' : '' }}>{{ $state }}</option>

          @endforeach

        </select>
        @if ($errors->has('state'))
        <span class="help-block">
          <strong>{{ $errors->first('state') }}</strong>
        </span>
        @endif
      </div>

      <!-- States Canada -->
      <div id="ca" class="states form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        <label for="state">Province</label>
        <select class="form-control" name="state" id="state" required>

          @foreach($states_canada::all() as $code => $state)

          <option value="{{ $code }}" {{ $user->state == $code ? ' selected="selected"' : '' }}>{{ $state }}</option>

          @endforeach

        </select>
        @if ($errors->has('state'))
        <span class="help-block">
          <strong>{{ $errors->first('state') }}</strong>
        </span>
        @endif
      </div>

      <!-- Non US / Can states -->
      <div id="na" class="states form-group{{ $errors->has('state') ? ' has-error' : '' }}">
        <label for="state">State</label>
        <input id="state" class="form-control " type="text" name="state" value="{{ $user->state ?: old('state') }}" />
        @if ($errors->has('state'))
        <span class="help-block">
          <strong>{{ $errors->first('state') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('zip') ? ' has-error' : '' }}">
        <label for="zip">Zip</label>
        <input id="zip" class="form-control " type="text" name="zip" value="{{ $user->zip ?: old('zip') }}" />
        @if ($errors->has('zip'))
        <span class="help-block">
          <strong>{{ $errors->first('zip') }}</strong>
        </span>
        @endif
      </div>

      

      <div class="form-group">
        <input id="submit" type="submit" value="Update" class="form-control btn btn-primary" />
      </div>
      
    </form>
    
  </div> <!-- .panel-body -->
</div>
@endsection

@section('footer')

@if(Session::has('message'))
<script type="text/javascript">
  swal("Good job!", "Your profile was updated", "success");
</script>
@endif

<script type="text/javascript">
  $(function() {

    $("select#country").on('change', function() {
      var c = $(this).val();

      $(".states").hide();

      if(c=='ca') {
        $("#ca").show();
      }
      else if(c=='us') {
        $("#us").show();
      }
      else {
        $("#na").show();
      }
    });

    var c = $("select#country").val();

    if(c=='ca') {
      $("#ca").show();
    }
    else if(c=='us') {
      $("#us").show();
    }
    else {
      $("#na").show();
    }



    /*$("form#update-profile").on("submit", function(e) {
        e.preventDefault();

        var formData = new FormData();

        console.log(formData);
        // $btn = $(this).find("#submit");

        // $btn.addClass("disabled").html('<i class="fa fa-cog fa-spin"></i>');

        $.ajax({
            type : "POST",
            url : "/user",
            data : formData,
            //contentType: "application/json; charset=UTF-8",
            success: function (response) { 

                swal("Good job!", "Profile updated", "success"); 

                // $btn.removeClass('disabled').html('Update');
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

      });*/

    });
  </script>


  @endsection
