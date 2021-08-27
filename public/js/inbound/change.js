//only check one
$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
});
