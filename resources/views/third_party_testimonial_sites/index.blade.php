@extends('layouts.app')

@section('content')
<div class="panel panel-default">
	<div class="panel-heading">
		External Testimonial Sites

		<a href="{{ url('externalLinks/create') }}" class="pull-right">
			Create
		</a>
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