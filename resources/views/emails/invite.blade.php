<p>{{ $body }}</p>

@if($video)
<p>{{ $user->first_name }} has sent a video with the request. Click below to watch it.</p>
<div style="border: 1px solid grey; padding: 5px;">
	<a taget="_blank" href="{{ url('videos/' . $video->id) }}">
	<img src="{{ $video->thumbnail }}" name="{{ $video->title }}" title="{{ $video->title }}">
	</a> <br />
	<a target="_blank" href="{{ url('videos/' . $video->id) }}">
	{{ url('videos/' . $video->id) }}
	</a>
</div>
<br />
@endif
Click the link below to add a testimonial.

<a href="{!! $url !!}">{{ $url }}</a>