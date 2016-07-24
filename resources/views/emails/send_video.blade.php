{{ $body }}

<p>Click on the link below to watch the video</p>
<p>
<a href="{{ url('videos/' . $video->id) }}">
@if($gif)
<img src="{{ $gif }}" name="{{ $video->title }}" title="{{ $video->title }}">
@else
<img src="{{ $video->thumbnail }}" name="{{ $video->title }}" title="{{ $video->title }}">
@endif

</a> <br />
<a href="{{ url('videos/' . $video->id) }}">
{{ url('videos/' . $video->id) }}
</a>
</p>
