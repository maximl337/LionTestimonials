@extends('layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		External Testimonial Sites
		<div class="btn-group pull-right" style="padding-bottom: 5px;">
			<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fa fa-gear"></i> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li>
					<a href="{{ url('externalLinks/create') }}">
						<i class="fa fa-plus"></i>  Create
					</a>
				</li>
				@if($links->count() > 0)
					<li>
						<a href="{{ url('externalLinks/send') }}">
							<i class="fa fa-envelope"></i>  Send in Email
						</a>
					</li>
				@endif
				
			</ul>
		</div>


	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">

		<table class="table">
			@foreach($links as $link)
			
			<tr>
				<td><a href="{{ $link->url }}">{{ $link->url }}</a></td>
				<td>{{ $link->provider }}</td>
				<td><a href="{{ url('externalLinks/' . $link->id .'/edit') }}" class="edit" data-id="{{ $link->id }}"><i class="fa fa-pencil"></i></a></td>
				<td><a href="#" class="delete-link" data-id="{{ $link->id }}"><i class="fa fa-trash"></i></a></td>
			</tr>
			

			@endforeach
		</table>
	</div>
	<!-- /.panel-body -->
</div>
@endsection

@section('footer')

<script type="text/javascript">	
	$(function() {

	// Delete link
	$(document).on("click", ".delete-link", function(e) {

		e.preventDefault();

		var $this = $(this);

		var id = $this.attr('data-id');

		$.ajax({

			url: "/externalLinks/" + id,
			type: "DELETE",
			data: {'_token': "{{ csrf_token() }}"}

		}).done(function(data) {

			$this.parents("tr").remove();

			swal("Good job!", "Link deleted", "success");

		}).fail(function(jqXHR, textStatus) {

			swal("Uh oh!", "Something went wrong", "error");
			
			console.log(jqXHR.responseText);
		});
	});

});
</script>

@endsection