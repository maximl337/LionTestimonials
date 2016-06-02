$(document).ready(function() {

  // Dropdown Hover Fix
  $('.navbar .dropdown').hover(function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown(200);
  }, function() {
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp(150)
  });

});