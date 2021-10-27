window.addEventListener("pageshow", () => { // clear the input if user clike back or forward arrow on broswer
    // cleanup input fields

    $('#client').val('');
    $('#number').val('');
    $('#innumber').val('');


    $('#change').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();
    });

});



