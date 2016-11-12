<div class="panel panel-default">
    <div class="panel-heading">Add Testimonial</div>

    <div class="panel-body">

        @if($errors->has('contact_id') || $errors->has('user_id'))

            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach

        @else

        <form  enctype="multipart/form-data" id="create-testimonial" method="post" action="{{ env('APP_URL') . 'testimonials' }}" role="form" novalidate>

          <input type="hidden" name="user_id" value="{{ $data['user']->id }}">

          <input type="hidden" name="contact_id" value="{{ $data['contact']->id }}">

          <input type="hidden" name="token" />

          <input type="hidden" name="thumbnail" />

          <input type="hidden" name="url" />

          {!! csrf_field() !!}

            <div class="form-group">
                <h3>You are writting a testimonial for {{ $data['user']->getName() }}</h3>
            </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">Your Email</label></span>
            <span class="help-block">We will not share your email or send you unsolicited mails.</span>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="your_email@example.com" required />
            @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
          @endif
      </div>

      <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
        <label for="rating">Rating</label>
        <br />
        <select id="rating" name="rating" class="form-control" required>
          <option disabled selected value> -- select an option -- </option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
      </select>
      <div class="rateit" data-rateit-backingfld="#rating" data-size="32"></div>
      @if ($errors->has('rating'))
      <span class="help-block">
          <strong>{{ $errors->first('rating') }}</strong>
      </span>
      @endif
  </div>

  <!-- Video -->

  <div id="video" class="form-group{{ $errors->has('token') ? ' has-error' : '' }}">
    <label for="video">Video</label>
    <ziggeo ziggeo-width=320
    ziggeo-height=240
    >
</ziggeo>
@if ($errors->has('token'))
<span class="help-block">
    <strong>{{ $errors->first('token') }}</strong>
</span>
@endif
</div>

<!-- EO video -->

<div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
    <label for="body">Testimonial</label>
    <textarea id="body" class="form-control" name="body" rows="10" placeholder="Write your testimonial here"></textarea>
    @if ($errors->has('body'))
    <span class="help-block">
        <strong>{{ $errors->first('body') }}</strong>
    </span>
    @endif
</div>

            <!-- <div class="form-group">
                {!! Recaptcha::render() !!}
                @if ($errors->has('g-recaptcha-response'))
                    <span class="help-block">
                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </span>
                @endif
            </div> -->

            <div class="form-group">
                <input type="submit" value="Send Testimonial" class="form-control btn btn-primary" />
            </div>

        </form>

        @endif
    </div> <!-- .panel-body -->
</div>
