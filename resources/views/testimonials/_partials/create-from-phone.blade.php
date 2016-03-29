<div class="phone panel panel-default">
    <div class="panel-heading">Add Testimonial</div>

    <div class="panel-body">

        @if($errors->has('contact_id') || $errors->has('user_id'))

            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach

        @else

            <form  enctype="multipart/form-data" id="create-testimonial" method="post" action="{{ env('APP_URL') . 'testimonials/phone' }}" role="form">

                <input type="hidden" name="user_id" value="{{ $data['user']->id }}">
                
                <input type="hidden" name="contact_id" value="{{ $data['contact']->id }}">

                {!! csrf_field() !!}

                <div class="form-group">
                    <h3>You are writting a testimonial for {{ $data['user']->getName() }}</h3>
                    <hr />
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
                    <select id="rating" name="rating" class="form-control" required>
                        <option disabled selected value> -- select an option -- </option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                    @if ($errors->has('rating'))
                        <span class="help-block">
                            <strong>{{ $errors->first('rating') }}</strong>
                        </span>
                    @endif
                </div>

                <!-- Video -->

                <div id="mobile-video" class="form-group{{ $errors->has('video') ? ' has-error' : '' }}">
                    <label for="video">Video</label>
                    <input class="form-control" id="video" type="file" name="video" accept="video/*,video/mp4,video/x-m4v" capture="camera">
                    @if ($errors->has('video'))
                        <span class="help-block">
                            <strong>{{ $errors->first('video') }}</strong>
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
                    <input type="submit" value="Create" class="form-control btn btn-primary" />
                </div>
                
            </form>

        @endif
    </div> <!-- .panel-body -->
</div>
