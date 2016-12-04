<div class="business-list">
	@if(!count($businesses))
		<p class="alert-warning text-center">No results found</p>
	@endif

	<ul class="list-group">
		@foreach($businesses as $business)
			
			<li class="list-group-item">
				
				<a href="#" class="business-item" data-name="{{ $business['name'] }}" data-id="{{ $business['id'] }}" data-url="{{ !empty($business['url']) ? $business['url'] : "" }}">
					
					<div class="row">
						
						@if(!empty($business['image_url']))
							<div class="col-md-2">
								
								<img style="width: 100%;" src="{{ $business['image_url'] }}">

							</div>
						@endif

						<div class="col-md-10">
							<h3>{{ $business['name'] }}</h3>
							<p>Rating: {{ $business['rating'] }} | Reviews: {{ $business['review_count'] }}</p>
							<p class="helper-text">{{ $business['address'] }}</p>
						</div>

					</div>

				</a>

			</li>

		@endforeach
	</ul>	
</div>
