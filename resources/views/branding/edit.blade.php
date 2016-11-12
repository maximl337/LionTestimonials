@extends('layouts.app')

@section('head')


@endsection

@section('content')
    
<div class="panel panel-default">
    <div class="panel-heading branding-primary-color">
        Edit Branding    
    </div>
    <!-- /.panel-heading -->    

    <div class="panel-body">
        
        <form id="branding" role="form" method="POST" action="{{ url('branding') }}">

            {!! csrf_field() !!}

            <div class="form-group alert alert-warning">
                <p class="helper-block">
                    Customize the <em><a href="{{ url('contact/register/'.Auth::id()) }}">client registration page</a></em> and <em>testimonial thank</em> you page color scheme.
                </p>
            </div>

            <div class="form-group{{ $errors->has('primary_color') ? ' has-error' : '' }}">

                <label for="">Primary color</label>

                <input id="primary-color" class="form-control" type="color" name="primary_color" value="{{ $branding->primary_color ?: '#000000' }}" />

                @if ($errors->has('primary_color'))
                    <span class="help-block">
                        <strong>{{ $errors->first('primary_color') }}</strong>
                    </span>
                @endif

            </div>


            <div class="form-group{{ $errors->has('background_color') ? ' has-error' : '' }}">

                <label for="">Background color</label>

                <input id="background-color" class="form-control" type="color" name="background_color" value="{{ $branding->background_color ?: '#FFFFFF' }}" />

                @if ($errors->has('background_color'))
                    <span class="help-block">
                        <strong>{{ $errors->first('background_color') }}</strong>
                    </span>
                @endif

            </div>

            <input type="hidden" name="text_color" value="{{ $branding->text_color ?: '#000000' }}">

            <div class="form-group">

                <input type="submit" class="form-control btn btn-primary" value="Save" />

            </div>
            
        </form>
        

    </div>
    <!-- /.panel-body -->
</div>
<!-- /.panel panel-default -->    

@endsection

@section('footer')

<script type="text/javascript">

$(function() { 

    $(document).on("input", "#primary-color", function(e) {

        e.preventDefault();

        var hex = $(this).val();

        var rbg = hexToRgb(hex);

        var textColor = colourIsLight(rbg[0], rbg[1], rbg[2]) 
                        ? 'black' 
                        : 'white';

        $('input[name="text_color"]').val(textColor);

        $(".branding-primary-color")
            .css("color", textColor);        

        $(".branding-primary-color")
            .css("background-color", hex);

    });

    $(document).on("input", "#background-color", function(e) {

        e.preventDefault();

        var hex = $(this).val();

        var rbg = hexToRgb(hex);

        var textColor = colourIsLight(rbg[0], rbg[1], rbg[2]) 
                        ? 'black' 
                        : 'white';

        $(".inner-border")
            .css("background-color", hex);

    });


}); // EO DOM READY

/**
 * [colourIsLight description]
 * @param  {[type]} r [description]
 * @param  {[type]} g [description]
 * @param  {[type]} b [description]
 * @return {[type]}   [description]
 */
var colourIsLight = function (r, g, b) {
  
    // Counting the perceptive luminance
    // human eye favors green color... 
    var a = 1 - (0.299 * r + 0.587 * g + 0.114 * b) / 255;

    return (a < 0.5);
}

/**
 * [hexToRgb description]
 * @param  {[type]} hex   [description]
 * @param  {[type]} asObj [description]
 * @return {[type]}       [description]
 */
function hexToRgb(hex, asObj) {

    return (function(res) {

        return res == null ? null : (function(parts) {

            return !asObj ? parts : { r : parts[0], g : parts[1], b : parts[2] }

        }(res.slice(1,4).map(function(val) { return parseInt(val, 16); })));

    }(/^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)));

}

</script>
@endsection