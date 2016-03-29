@foreach ($testimonials as $testimonial)

    <section>
        <h2>Rating: {{ $testimonial->rating }}</h2>
        <p>{{ $testimonial->body }}</p>
        <p>
        	@if(!empty($testimonial->video)) 
            	<iframe style="width: 100%;" id="video" src="{{ url('video') . '/' . $testimonial->id  }}"></iframe>
            @endif
        </p>
        <p>From: <strong>{{ $testimonial->contact()->first()->first_name }}</strong> {{ $testimonial->created_at->diffForHumans() }}</p>
        <hr />
    </section>

@endforeach

{{ $testimonials->render() }}