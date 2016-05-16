@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">
					Send by email
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					
					<form id="send_by_email" method="POST" action="{{ url('videos/send/email') }}" role="form">
						{!! csrf_field() !!}
						<input type="hidden" name="video_id" value="{{ $data['video']->id }}">
						
						<div class="form-group">
							@include('errors.list')
						</div>
						
						<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
							<label for="contact">Contact</label>
							<select name="contact_id" id="contact" class="form-control" required>
								<option selected="selected" disabled="disabled">Select a contact to send to</option>
								@foreach($data['contacts'] as $contact)
									<option value="{{ $contact->id }}"> {{ $contact->getName() . ' - ' . $contact->email }}  </option>
								@endforeach
							</select>
														</div>
						<div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
							<label for="message">Message</label>
							<p class="help-block">Customize the message</p>
							<textarea id="message" class="form-control" name="message" rows="10" placeholder="Enter a custom message">{{ Auth::user()->getName() }} has sent you a video message.</textarea>
							
						</div>
						<div class="form-group">
							<p class="text-muted">The following will be added to the message</p>
							<p>Click on the link below to watch the video</p>
							<p>
								<a href="#">
								<img src="{{ $data['video']->thumbnail }}" name="{{ $data['video']->title }}" title="{{ $data['video']->title }}">
								</a> <br />
								<a href="#">
									<em>link to the video</em>
								</a>
							</p>
						</div>

						<div class="form-group">
							<input class="form-control btn btn-primary" type="submit" value="Send" />
						</div>
						
						
						
					</form>
					

				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel panel-default -->
		</div>
		<!-- /.col-md-10 col-md-offset-1 -->
	</div>
	<!-- /.row -->
</div>
<!-- /.container -->

@endsection