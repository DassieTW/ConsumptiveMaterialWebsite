$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#form1").on("submit", function (e) {
        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").hide();

        // var client = $("#client").val();
        var isn = $("#number").val();
        var check = $("#date").is(":checked");
        var begin = $("#begin").val();
        var end = $("#end").val();
        var success = true;

        if (isn === "") isn = null;

        // sessionStorage.setItem("inboundclient", JSON.stringify(client)); // for later vue to post request
        sessionStorage.setItem("inboundisn", JSON.stringify(isn)); // for later vue to post request
        sessionStorage.setItem("inboundcheck", true); // for later vue to post request
        sessionStorage.setItem("inboundbegin", JSON.stringify(begin)); // for later vue to post request
        sessionStorage.setItem("inboundend", JSON.stringify(end)); // for later vue to post request

        window.location.href = "inquire";
    });
});
