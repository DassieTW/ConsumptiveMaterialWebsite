sessionStorage.clear();

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {
    $("#uploadbasic").on("submit", function (e) {

        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        var data = [];
        var row = [];
        var title = $("#title0").val();
        var count = $("#count").val();

        for (let i = 0; i < count; i++) {
            if ($("#data0" + i).val() !== null && $("#data0" + i).val() !== undefined && $("#data0" + i).val() !== ' ') {
                data.push($("#data0" + i).val());
                row.push(i.toString());
            } else {
                continue;
            }
        }

        if (row.length == 0) {
            notyf.open({
                type: 'warning',
                message: Lang.get('basicInfoLang.nodata'),
                duration: 3000, //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
            return false;
        }

        $.ajax({
            type: "POST",
            url: "insertuploadbasic",
            data: {
                title: title,
                data: data,
                row: row,
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
                $('body').loadingModal('destroy');

            },
            success: function (data) {

                console.log(data.choose);
                console.log(data.record);

                var mess = Lang.get('basicInfoLang.total') + ' : ' + row.length + Lang.get('basicInfoLang.record') + ' ' +
                    Lang.get('basicInfoLang.data') + ' ， ' + Lang.get('basicInfoLang.success') + ' ' + Lang.get('basicInfoLang.new') +
                    ' : ' + data.record + ' ' + data.choose + ' ' + Lang.get('basicInfoLang.data');

                alert(mess);

                var mess2 = Lang.get('basicInfoLang.yellowrepeat');

                alert(mess2);

                for (let i = 0; i < row.length; i++) {

                    var same = row.filter(function (v) {
                        return (data.check).indexOf(v) > -1
                    });
                    var diff = row.filter(function (v) {
                        return (data.check).indexOf(v) == -1
                    });
                }
                for (let i = 0; i < same.length; i++) {
                    $('#row' + same[i]).remove();
                }
                for (let i = 0; i < diff.length; i++) {

                    document.getElementById("row" + diff[i]).style.backgroundColor = "yellow";
                }
            },
            error: function (err) {
                console.log(err.status);

                // transaction error
                var mess = err.responseJSON.message;
                window.alert(mess);
                window.location.reload();
            },
        });
    });
});
