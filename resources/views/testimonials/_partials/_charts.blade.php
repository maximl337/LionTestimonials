<div class="row">   
	@if(!empty($average_rating))
	    <div class="col-md-6 text-center">

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
	@endif

	@if(!empty($review_count))
	    <div class="col-md-6">
	        <canvas id="chartTwo"></canvas>
	    </div>
    @endif
</div>

@section('footer')

<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

@if(!empty($average_rating) && !empty($review_count))
<script type="text/javascript">
(function() {

	$('#average_rating_stars').barrating({
    	theme: 'bootstrap-stars',
    	readonly: true
  	});

    var ctx = document.getElementById("chartTwo").getContext('2d');

    // build array
    console.log("{{ $review_count['yelp'] }}");

	var myChart = new Chart(ctx, {
	  type: 'pie',
	  data: {
	    labels: ["Google", "Yelp", "Sell with reviews"],
	    datasets: [{
	      backgroundColor: [
	        "#3498db",
	        "#e74c3c",
	        "#34495e"
	      ],
	      data: [
	      	parseInt("{{ $review_count['google'] }}"), 
	      	parseInt("{{ $review_count['yelp'] }}"), 
	      	parseInt("{{ $review_count['local'] }}")
	      ]
	    }]
	  }
	});

})();
	

</script>
@endif
@endsection