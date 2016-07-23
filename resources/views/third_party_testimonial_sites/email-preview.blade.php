@extends('layouts.app')

@section('content')

<div class="panel panel-default">

  <div class="panel-heading">Preview <!-- <a href="{{ url('contacts/external/email/self') }}" class="pull-right"> Send to self</a> --></div>

  <div class="panel-body">
    
    <form id="" method="POST" action="{{ url('externalLinks/send') }}" role="form">

      {!! csrf_field() !!}

      <div class="form-group{{ $errors->has('contact_id') ? ' has-error' : '' }}">
        <label for="contact_id">Select a contact</label>
        <select id="contact_id" name="contact_id" class="form-control">
          @foreach($contacts as $contact)
              <option value="{{ $contact->id }}">{{ $contact->getName() }}</option>
          @endforeach
        </select>
        @if ($errors->has('contact_id'))
        <span class="help-block">
          <strong>{{ $errors->first('contact_id') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
        <label for="message">Customize Message</label>
        <textarea style="padding: 10px; font-size: 20px;" id="message" class="form-control" name="message" rows="6"> Hi, &#013;&#010;{{ Auth::user()->getName() }} has requested a testimonial from you for his services on some of his profiles. &#013;&#010;This should take no more than a couple of minutes.</textarea>
        @if ($errors->has('message'))
        <span class="help-block">
          <strong>{{ $errors->first('message') }}</strong>
        </span>
        @endif
      </div>

      <div class="form-group{{ $errors->has('links') ? ' has-error' : '' }}">
        <label>Select the Links you would like to add to this message</label>
        <br />
        @foreach($links as $link)
        <label>
          <input name="links[]" value="{{ $link->id }}" type="checkbox">
          {{ $link->url }}    
        </label> <br />
        @endforeach

        @if ($errors->has('links'))
        <span class="help-block">
          <strong>{{ $errors->first('links') }}</strong>
        </span>
        @endif
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
