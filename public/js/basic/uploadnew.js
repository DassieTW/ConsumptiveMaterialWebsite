$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {
    $("#uploadnew").on("submit", function (e) {

        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        var number = [];
        var name = [];
        var format = [];
        var price = [];
        var money = [];
        var unit = [];
        var mpq = [];
        var moq = [];
        var lt = [];
        var month = [];
        var gradea = [];
        var belong = [];
        var send = [];
        var safe = [];
        var row = [];

        var count = $("#count").val();

        for (let i = 0; i < count; i++) {
            if ($("#data0a" + i).val() !== null && $("#data0a" + i).val() !== undefined && $("#data0a" + i).val() !== ' ') {
                number.push($("#data0a" + i).val());
                name.push($("#data1a" + i).val());
                format.push($("#data2a" + i).val());
                price.push($("#data3a" + i).val());
                money.push($("#data4a" + i).val());
                unit.push($("#data5a" + i).val());
                mpq.push($("#data6a" + i).val());
                moq.push($("#data7a" + i).val());
                lt.push($("#data8a" + i).val());
                month.push($("#data9a" + i).val());
                gradea.push($("#data10a" + i).val());
                belong.push($("#data11a" + i).val());
                send.push($("#data12a" + i).val());
                safe.push($("#data13a" + i).val());
                row.push(i.toString());
            } else {
                continue;
            }
        }

        for (let i = 0; i < number.length; i++) {
            if (number[i].length !== 12) {
                var row = i + 1;
                var mess = Lang.get('basicInfoLang.row') + ' : ' + row + ' ' + Lang.get('basicInfoLang.isnlength');
                $("#data0a" + i).addClass("is-invalid");


                notyf.open({
                    type: 'warning',
                    message: mess,
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
            if (month[i] == '否' || month[i] == 'No' && safe[i] == "") {
                var row = i + 1;
                var mess = Lang.get('basicInfoLang.row') + ' : ' + row + ' ' + Lang.get('basicInfoLang.safeerror');
                $("#data13a" + i).addClass("is-invalid");


                notyf.open({
                    type: 'warning',
                    message: mess,
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
        } // for

        if (number.length == 0) {
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
        var data = [];
        data.push(number);
        data.push(name);
        data.push(format);
        data.push(price);
        data.push(money);
        data.push(unit);
        data.push(mpq);
        data.push(moq);
        data.push(lt);
        data.push(month);
        data.push(gradea);
        data.push(belong);
        data.push(send);
        data.push(safe);

        $.ajax({
            type: "POST",
            url: "insertuploadmaterial",
            data: {
                AllData: JSON.stringify(data),
                count: count,
                row: row,
            },
            // dataType: 'json', // let's set the expected response format
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

                var mess = Lang.get('basicInfoLang.total') + ' : ' + row.length + ' ' + Lang.get('basicInfoLang.record') + ' ' +
                    Lang.get('basicInfoLang.matsdata') + ' ， ' + Lang.get('basicInfoLang.success') + ' ' + Lang.get('basicInfoLang.new') +
                    ' : ' + data.record + ' ' + Lang.get('basicInfoLang.matsdata');

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
                    count = count - 1;
                }
                for (let i = 0; i < diff.length; i++) {

                    document.getElementById("row" + diff[i]).style.backgroundColor = "yellow";
                }
                // window.location.href = "/basic";
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
