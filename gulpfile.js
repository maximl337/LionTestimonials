var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.styles([
    	'bootstrap-stars.css',
    	'rateit.css'	
    ]).scripts([
        'RecordRTC.js',
        'gumadapter.js',
        'jquery.rateit.min.js',
        'star_rating.min.js'
    ]).version(["public/build/css/all.css", "public/build/js/all.js"]);

    
    
}); 
