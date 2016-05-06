{{ $msg }}

<p>Click on the link below to watch the video</p>
<p>
<a href="{{ url('videos/' . $video->id) }}">
<img src="{{ $video->thumbnail }}" name="{{ $video->title }}" title="{{ $video->title }}">
</a> <br />
<a href="{{ url('videos/' . $video->id) }}">
{{ url('videos/' . $video->id) }}
</a>
</p>

<p>Click on the link below to watch it</p>

<a href="{!! $url !!}">{{ $url }}</a>
