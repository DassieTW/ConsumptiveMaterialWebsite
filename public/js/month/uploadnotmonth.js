$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$('#uploadnotmonth').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var client = [];
    var number = [];
    var sxb = [];
    var say = [];
    var amount = [];
    var month = [];
    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
        client.push($("#data1" + i).val());
        number.push($("#data2" + i).val());
        sxb.push($("#data0" + i).val());
        say.push($("#data5" + i).val() + ' ' + $("#data4" + i).val());
        amount.push($("#data3" + i).val());
        month.push($("#data6" + i).val());
    }

    for (let i = 0; i < count; i++) {
        if ($("#data6" + i).val() === '是') {

            if ($("#data5" + i).val() === null || $("#data4" + i).val() === null) {
                $("#data5" + i).addClass("is-invalid");
                $("#data4" + i).addClass("is-invalid");
                i++;
                var mess = Lang.get('monthlyPRpageLang.row') + ' ' + i + ' ' + Lang.get('monthlyPRpageLang.errormonth');
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
            } else {
                continue;
            }
        } else {
            continue;
        }

    }

    $.ajax({
        type: 'POST',
        url: "notmonthsubmit",
        data: {
            client: client,
            number: number,
            sxb: sxb,
            say: say,
            amount: amount,
            count: count,
            month: month
        },
        beforeSend: function () {
            // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
            $('body').loadingModal({
                text: 'Loading...',
                animation: 'circle'
            });
        },
        complete: function () {
            $('body').loadingModal('hide');
            $('body').loadingModal('destroy');
        },
        success: function (data) {

            var mess = Lang.get('monthlyPRpageLang.total') + ' : ' + data.record + ' ' + Lang.get('monthlyPRpageLang.record') +
                Lang.get('monthlyPRpageLang.notmonth') + Lang.get('monthlyPRpageLang.add') + Lang.get('monthlyPRpageLang.success');
            alert(mess);
            window.location.href = "importnotmonth";
            //window.location.href = "member.newok";

        },
        error: function (err) {
            //transaction error
            if (err.status == 421) {
                console.log(err.status);
                alert(err.responseJSON.message);
                window.location.reload();
            }
        },
    });
});
