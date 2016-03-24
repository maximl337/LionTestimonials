@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">

                <div class="panel-heading">Preview <a href="{{ url('contacts/email/self') }}" class="pull-right"> Send to self</a></div>

                <div class="panel-body">

                    <p>Hi {{ $contact->first_name }},</p>
                    <p>{{ Auth::user()->getName() }} has requested a testimonial from you for his services. This should take no more than a couple of minutes. Click the link below to send the testimonial </p>
                    <p><a href="#">Click here</a></p>
                    
                
                    <form id="" method="POST" action="{{ url('contacts/email/send') }}" role="form">

                        {!! csrf_field() !!}

                        <input type="hidden" name="contact_id" value="{{ $contact->id }}">

                        <div class="form-group">
                            <input id="" class="form-control btn btn-primary" type="submit" value="Looks good. Send it." />
                        </div>
                        @if ($errors->has('contact_id'))
                            <span class="help-block">
                                <strong>Contact id not found in request</strong>
                            </span>
                        @endif
                        
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
