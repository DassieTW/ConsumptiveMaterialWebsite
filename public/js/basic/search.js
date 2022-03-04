$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    $(".form-check-input").on("change", function () {

        var check = $('input[name=numberradio]:checked').val();
        if (check == 1 ) {
            // $("#number").prop('required', true);
            $("#numberarea").prop('required', false);
        } else {
            $("#number").prop('required', false);
            $("#numberarea").prop('required', true);
        }

    });
});
