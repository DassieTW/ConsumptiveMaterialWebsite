$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});


$("#uploadinventory").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var client = [];
    var number = [];
    var amount = [];
    var position = [];
    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
        client.push($("#data0" + i).val());
        number.push($("#data1" + i).val());
        amount.push($("#data2" + i).val());
        position.push($("#data3" + i).val());
    }

    $.ajax({
        type: "POST",
        url: "insertuploadinventory",
        data: {
            client: client,
            number: number,
            position: position,
            amount: amount,
            count: count,
        },

        beforeSend: function () {
            // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });
        },
        complete: function () {
            $("body").loadingModal("hide");
        },

        success: function (data) {
            console.log(number);
            var mess =
                Lang.get("inboundpageLang.total") +
                " " +
                data.message +
                " " +
                Lang.get("inboundpageLang.record") +
                Lang.get("inboundpageLang.stockupload") +
                Lang.get("inboundpageLang.success");
            alert(mess);
            window.location.href = "/inbound";
        },
        error: function (err) {
            console.log(err.status);

            var mess = err.responseJSON.message;
            window.alert(mess);
            window.location.reload();

        },
    });

});
