<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Lion Testimonials</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>

    <!-- Vendor styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/animatecss/3.5.1/animate.min.css">

    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/ionicons.css">
    <link rel="stylesheet" href="/css/bootstrap-social.css">

    <style type="text/css">
        .navbar-inverse {
            margin-bottom: 0px;
        }

        body > .container {
            padding-top: 30px;
        }
    </style>

    @yield('head')

</head>
<body id="app-layout">
    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-nav" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">
                    @if (Auth::guest())
                    <a class="navbar-brand" href="{{ url('/') }}"><img src="/images/logo.png" alt="" title="" class="logo"/></a>
                    @else
                    <a class="navbar-brand" href="{{ url('/home') }}"><img src="/images/logo.png" alt="" title="" /></a>
                    @endif
                </a>
            </div>

        </div>
    </nav>

    <div class="container">
        <div class="col-md-12">
            @yield('content')
        </div>
        <!-- /.col-md-12 -->
    </div>
    <!-- /.container -->



    <!-- JavaScripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- Vendor JS -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<!-- <script src="https://www.WebRTC-Experiment.com/RecordRTC.js"></script>
    <script src="https://cdn.webrtc-experiment.com/gumadapter.js"></script> -->
    <script src="/js/all.js"></script>
    <script src="/js/custom.js"></script>



    @if(Session::has('success'))
    <script type="text/javascript">
        swal("Good job!", "{{ Session::get('success') }}", "success");
    </script>
    @endif
    @if(Session::has('error'))
    <script type="text/javascript">
        swal("Uh oh!", "{{ Session::get('error') }}", "error");
    </script>
    @endif

    @yield('footer')

</body>
</html>
