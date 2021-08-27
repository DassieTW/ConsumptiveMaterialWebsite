//only check one
$('.innumber').on('change', function() {
    $('.innumber').not(this).prop('checked', false);
});
