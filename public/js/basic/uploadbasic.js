$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$("#uploadbasic").on("submit", function (e) {
    console.log(12);

    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var data = [];
    var title = $("#title0").val();
    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
        data.push($("#data0" + i).val());
    }

    console.log(title);
    $.ajax({
        type: "POST",
        url: "insertuploadbasic",
        data: {
            title: title,
            data: data,
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
            var mess =
                Lang.get("basicInfoLang.total") +
                " " +
                data.message +
                " " +
                Lang.get("basicInfoLang.record") +
                data.choose +
                Lang.get("basicInfoLang.new") +
                Lang.get("basicInfoLang.success");
            alert(mess);
            window.location.href = "/basic";
        },
        error: function (err) {
            console.log(err.status);
            //data重複
            if (err.status == 420) {
                var mess =
                    Lang.get("basicInfoLang.row") +
                    " : " +
                    err.responseJSON.message +
                    " " +
                    Lang.get("basicInfoLang.repeat");
                window.alert(mess);
                window.location.reload();
            }
            // transaction error
            else {
                var mess = err.responseJSON.message;
                window.alert(mess);
                window.location.reload();
            }
        },
    });
});
