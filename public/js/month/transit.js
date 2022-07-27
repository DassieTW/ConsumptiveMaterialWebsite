$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form1').on('submit', function (e) {
        e.preventDefault();


        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var client = $("#client").val();
        var isn = $("#number").val();
        var send = $("#send").val();
        if (isn === "") isn = null;

        sessionStorage.setItem("transitclient", JSON.stringify(client)); // for later vue to post request
        sessionStorage.setItem("transitisn", JSON.stringify(isn)); // for later vue to post request
        sessionStorage.setItem("transitsend", JSON.stringify(send)); // for later vue to post request

        window.location.href = "transitsearch";

    });

});
