@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">Preview <a href="{{ url('contacts/email/self') }}" class="pull-right"> Send to self</a></div>

                <div class="panel-body">

                    
                    
                
                    <form id="" method="POST" action="{{ url('contacts/email/send') }}" role="form">

                        {!! csrf_field() !!}

                        <input type="hidden" name="contact_id" value="{{ $contact->id }}">

                        <div class="form-group">
                            <label for="message">Customize Message</label>
                            <textarea style="padding: 10px; font-size: 20px;" id="message" class="form-control" name="message" rows="6"> Hi {{ $contact->first_name }}, &#013;&#010;{{ Auth::user()->getName() }} has requested a testimonial from you for his services. &#013;&#010;This should take no more than a couple of minutes.</textarea>
                        </div>

                        <div class="form-group">
                            <label>The link below will be added to your customized message.</label>
                            <pre><a href="#">Click here</a> to submit testimonial.</pre>
                        </div>

                        @if ($errors->has('contact_id'))
                            <span class="help-block">
                                <strong>Contact id not found in request</strong>
                            </span>
                        @endif

                        <div class="form-group">
                            <input id="" class="form-control btn btn-primary" type="submit" value="Looks good. Send it." />
                        </div>
                        
                    </form>
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
