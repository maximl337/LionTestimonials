<div class="row">   
	@if(!empty($average_rating))
	    <div class="col-md-6 text-center">
			<div style="display: flex;flex-direction: column; justify-content: center;">
		    	<h3>Average Rating</h3>
				<h1 style="font-size: 9em;">{{ $average_rating }}</h1>

				<select id="average_rating_stars">
					@foreach(range(1, 5) as $rating)
						<option value="{{ $rating }}" {{ $rating == $average_rating ? ' selected="selected"' : '' }}>
							{{ $rating }}
						</option>
					@endforeach
				</select>
			</div>
	    </div>
	@endif

	@if(!empty($review_count))
	    <div class="col-md-6">
	        <canvas id="chartTwo"></canvas>
	    </div>
    @endif
</div>

