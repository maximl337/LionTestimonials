@foreach ($testimonials as $testimonial)

<section>
  <h2>Rating: {{ $testimonial->rating }}</h2>
  <p>{{ $testimonial->body }}</p>
  <p>
   @if(!empty($testimonial->url)) 
   <!-- Simple video example -->
   <video src="{{ $testimonial->url }}" 
    poster="{{ $testimonial->thumbnail }}"
    controls
    >
    Sorry, your browser doesn't support embedded videos, 
    but don't worry, you can <a href="{{ $testimonial->url }}">download it</a>
    and watch it with your favorite video player!
  </video>
  @endif
</p>
<p>From: <strong>{{ $testimonial->contact()->first()->first_name }}</strong> {{ $testimonial->created_at->diffForHumans() }}</p>
<hr />
</section>

@endforeach

{!! $testimonials->render() !!}