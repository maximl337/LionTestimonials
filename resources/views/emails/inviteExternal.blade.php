{{ $msg }}

<p>Click on the links below</p>
@foreach($urls as $url)
	<a href="{!! $url !!}">{{ $url }}</a><br />
@endforeach