


//only check one
$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
    var checkedValue = $('.basic:checked').val();
    if(checkedValue === '1' )
    {
        $('#position').attr('disabled', true);
    }
    else{
        $('#position').attr('disabled', false);
    }
});



