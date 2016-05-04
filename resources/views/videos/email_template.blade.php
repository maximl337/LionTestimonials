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
						<div class="form-group">
							<div class="form-group">
								<label for="contact">Contact</label>
								<select id="contact" class="form-control" required>
									<option selected="selected" disabled="disabled">Select a contact to send to</option>
									@foreach($data['contacts'] as $contact)
										<option value="{{ $contact->id }}"> {{ $contact->getName() . ' - ' . $contact->email }}  </option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label for="message">Message</label>
								<textarea id="message" class="form-control" name="message" rows="10" placeholder="Enter a custom message"></textarea>
							</div>
							<div class="form-group">
								<p class="helper-block">The following will be added to the message</p>
								<p>Click on the link below to watch the video</p>
								<p><a href="#"><em>link to the video</em></a></p>
							</div>

							<div class="form-group">
								<input id="" class="form-control btn btn-primary" type="text" value="Send" />
							</div>
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