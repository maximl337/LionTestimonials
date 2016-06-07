<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title></title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <h1>Testimonials</h1>

  <div class="testimonials">
    @include('testimonials._partials.testimonials')
  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

  <script>

    $(window).on('hashchange', function() {
      if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
          return false;
        } else {
          getTestimonials(page);
        }
      }
    });

    $(document).ready(function() {
      $(document).on('click', '.pagination a', function (e) {
        getTestimonials($(this).attr('href').split('page=')[1]);
        e.preventDefault();
      });
    });

    function getTestimonials(page) {
      $.ajax({
        url : '?page=' + page,
        dataType: 'json',
      }).done(function (data) {
        $('.testimonials').html(data);
        location.hash = page;
      }).fail(function () {
        alert('Posts could not be loaded.');
      });
    }
  </script>

</body>
</html>