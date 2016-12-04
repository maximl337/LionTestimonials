@extends('layouts.app')

@section('head')

@endsection

@section('content')

<div class="panel panel-default">
    <div class="panel-heading">
        Your Testimonials
        <div class="btn-group pull-right">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                filter <span class="caret"></span>
            </a>
        </div>
    </div>

    <div class="panel-body">

        @include('testimonials._partials._charts', ['average_rating' => $average_rating, 'review_count' => $testimonials_by_providers])

        <div class="row">
            <hr />
            <div class="col-md-12">
                <p class="pull-right">
                    <a href="{{ url('testimonials') }}" class="btn btn-primary"> Your Testimonials: {{ $testimonials_by_providers['local'] }}</a>
                </p>
            </div>
        </div>
        
       
        <div class="row">
            @foreach($testimonials as $testimonial)        
                
                <div class="col-md-12">

                    <div class="row">

                        <hr />
                        
                        <div class="col-md-2">

                            @if($testimonial->vendor()->first()->provider == 'yelp')
                                
                                <div style="width: 100%;">
                                    <img src="//i.imgur.com/FJKbhrg.png" style="width:100%;" />
                                </div>
                            
                            @elseif($testimonial->vendor()->first()->provider == 'google')

                                <div style="width: 100%;">
                                    <img src="//i.imgur.com/WCpc79f.jpg" style="width:100%;" />
                                </div>
                            @endif


                            
                        </div>

                        <div class="col-md-10">
                        
                            <h4>{{ str_limit($testimonial->body, 150) }}</h4>
                            <p class="small-text">
                                <strong>{{ $testimonial->author }}</strong> 
                                <em>{{ $testimonial->review_date->diffForHumans() }}</em>
                                <a href="{{ $testimonial->url }}" target="_blank">view</a>
                            </p>


                            <select class="rating_stars">
                                @foreach(range(1, 5) as $rating)
                                    <option value="{{ $rating }}"
                                        {{ $rating == $testimonial->rating ? ' selected="selected"' : '' }}
                                    >
                                    {{ $rating }}</option>
                                @endforeach
                            </select>
                    
                        </div>

                    </div>
                    <!-- /.row -->

                    
                </div>
                <!-- /.col-md-12 -->
                
            @endforeach
        </div><!-- .row -->

        {!! $testimonials->render() !!}
    </div> <!-- .panel-body -->
</div>

@endsection

@section('footer')

<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

<script type="text/javascript">
    $(function() {

        $('.rating_stars').barrating({
            theme: 'bootstrap-stars',
            readonly: true

        });

    });
</script>



@if(!empty($average_rating) && !empty($testimonials_by_providers))
<script type="text/javascript">
(function() {

    $('#average_rating_stars').barrating({
        theme: 'bootstrap-stars',
        readonly: true
    });

    var ctx = document.getElementById("chartTwo").getContext('2d');

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
            parseInt("{{ $testimonials_by_providers['google'] }}"), 
            parseInt("{{ $testimonials_by_providers['yelp'] }}"), 
            parseInt("{{ $testimonials_by_providers['local'] }}")
          ]
        }]
      }
    });

})();
    

</script>
@endif

@endsection
