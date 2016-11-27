<form id="create_contact_form" method="POST" action="{{ url('contact/register/' . $user->id) }}" role="form">

    {!! csrf_field() !!}

    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
        <label for="first_name">First Name</label>
        <input id="first_name" class="form-control " type="text" name="first_name" value="{{ old('first_name') }}" />
        @if ($errors->has('first_name'))
        <span class="help-block">
            <strong>{{ $errors->first('first_name') }}</strong>
        </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
        <label for="last_name">Last Name</label>
        <input id="last_name" class="form-control " type="text" name="last_name" value="{{ old('last_name') }}" />
        @if ($errors->has('last_name'))
        <span class="help-block">
            <strong>{{ $errors->first('last_name') }}</strong>
        </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email">Email</label>
        <input id="email" class="form-control " type="text" name="email" value="{{ old('email') }}" />
        @if ($errors->has('email'))
        <span class="help-block">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
        <label for="phone">Phone</label>
        <input id="phone" class="form-control " type="text" name="phone" value="{{ old('phone') }}" />
        @if ($errors->has('phone'))
        <span class="help-block">
            <strong>{{ $errors->first('phone') }}</strong>
        </span>
        @endif
    </div>

    <div class="form-group">
        <input type="submit" value="Create" class="form-control btn btn-primary" style="{{ !empty($primary_color) ? 'background-color: ' . $primary_color : '' }}">
    </div>

</form>