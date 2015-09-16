$(function() {
  // Focus state for append/prepend inputs
  $('.input-group').on('focus', '.form-control', function () {
    $(this).closest('.form-group, .navbar-search').addClass('focus');
  }).on('blur', '.form-control', function () {
    $(this).closest('.form-group, .navbar-search').removeClass('focus');
  });
});