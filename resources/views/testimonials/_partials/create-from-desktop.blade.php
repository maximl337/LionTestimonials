<div class="desktop panel panel-default">
    <div class="panel-heading">Add Testimonial</div>

    <div class="panel-body">

        @if($errors->has('contact_id') || $errors->has('user_id'))

            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach

        @endif
                
        <div class="form-group">
            <h3>You are writting a testimonial for {{ $data['user']->getName() }}</h3>
            <hr />
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label for="email">Your Email</label>
            <span class="help-block">We will not share your email or send you unsolicited mails.</span>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="your_email@example.com" />
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('rating') ? ' has-error' : '' }}">
            <label for="rating">Rating</label><br />
            <select id="rating" name="rating" class="hidden">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <div class="rateit" data-rateit-backingfld="#rating"></div>
            @if ($errors->has('rating'))
                <span class="help-block">
                    <strong>{{ $errors->first('rating') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
            <label for="body">Testimonial</label>
            <textarea id="body" class="form-control" name="body" rows="10" placeholder="Write your testimonial here"></textarea>
            @if ($errors->has('body'))
                <span class="help-block">
                    <strong>{{ $errors->first('body') }}</strong>
                </span>
            @endif
        </div>

        <!-- Video -->

        <div style="display: none;" id="mobile-video" class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
            <label for="body">Video</label>
            <input class="form-control" type="file" name="video" accept="video/*" capture>
            @if ($errors->has('body'))
                <span class="help-block">
                    <strong>{{ $errors->first('body') }}</strong>
                </span>
            @endif
        </div>

        <div style="display: none;" id="desktop-video" class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
            
            <label for="body">Video</label>
            <section class="experiment recordrtc">
                <h2 style="height: 0px; overflow: hidden;" class="header">
                    <select class="recording-media">
                        <option value="record-video">Video</option>
                        <option value="record-audio">Audio</option>
                        <option value="record-screen">Screen</option>
                    </select>
                    
                    into
                    <select class="media-container-format">
                        <option>WebM</option>
                        <option disabled>Mp4</option>
                        <option disabled>WAV</option>
                        <option disabled>Ogg</option>
                        <option>Gif</option>
                    </select>
                </h2>

                <button class="btn btn-small btn-primary">Start Recording</button>

                <br />

                <video id="video" controls muted></video>

                 <br>

                <div style="text-align: center; display: none;">
                    <button class="safe-hide" id="save-to-disk">Save To Disk</button>
                    <button class="safe-hide" id="open-new-tab">Open New Tab</button>
                    <button class="form-control btn btn-primary" id="upload-to-server">Submit</button>
                </div>
                
               
            </section>
        </div>
        

        <div class="form-group">
            <button  id="submit-without-video" class="btn btn-primary form-control">Submit without video</button>
        </div>
        
        <!-- EO video -->
    </div> <!-- .panel-body -->
</div>


