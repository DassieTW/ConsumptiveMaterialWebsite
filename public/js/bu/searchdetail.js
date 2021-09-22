


//only check one
/*$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
    var checkedValue = $('.basic:checked').val();
});*/

$(function() {
    $('.basic').on('change', function() {
        if($(this).prop('checked') === false) {
          $(this).prop('checked', true);
        }
        $('.basic').not(this).prop("checked", false);
    });
});


