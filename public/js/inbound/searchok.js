window.addEventListener("pageshow", () => { // clear the input if user clike back or forward arrow on broswer
    // cleanup input fields

    // $('#client').val('');
    // $('#number').val('');
    // $('#innumber').val('');
});

//only check one
$('.innumber').on('change', function() {
    $('.innumber').not(this).prop('checked', false);
});


