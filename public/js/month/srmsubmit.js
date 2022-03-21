$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    $('#srmsubmit').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var check = [];
        $("input:checkbox[name=innumber]:checked").each(function () {
            check.push($(this).val());
        });

        var checkcount = check.length;
        var count = $("#count").val();
        var client = [];
        var number = [];
        var sxbamount = [];
        var buyamount = [];
        var srmnumber = [];
        var sxbnumber = [];

        for (let i = 0; i < checkcount; i++) {
            client.push($("#client" + check[i]).val());
            number.push($("#number" + check[i]).val());
            sxbamount.push($("#sxbamount" + check[i]).val());
            buyamount.push($("#buyamount" + check[i]).val());
            srmnumber.push($("#srmnumber" + check[i]).val());
            if ($("#sxbnumber" + check[i]).val() == "") {
                row = parseInt(check[i]) + 1;
                mess = Lang.get('monthlyPRpageLang.entersxb') + ' ' + Lang.get('monthlyPRpageLang.row') +
                    ' : ' + row;
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
                $("#sxbnumber" + check[i]).addClass("is-invalid");
                return false;
            } else {
                sxbnumber.push($("#sxbnumber" + check[i]).val());
            }
        }



        if (checkcount == []) {
            notyf.open({
                type: 'warning',
                message: Lang.get('monthlyPRpageLang.nodata'),
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
            type: 'POST',
            url: "srmsubmit",
            data: {
                client: client,
                number: number,
                sxbamount: sxbamount,
                srmnumber: srmnumber,
                sxbnumber: sxbnumber,
                count: count,
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

                var mess = Lang.get('monthlyPRpageLang.total') + ' ' + (data.message) + ' ' + Lang.get('monthlyPRpageLang.record') +
                    Lang.get('monthlyPRpageLang.SRM') + ' ' + Lang.get('monthlyPRpageLang.submit') + ' ' + Lang.get('monthlyPRpageLang.success');
                alert(mess);
                window.location.href = "srm";

            },
            error: function (err) {
                //transaction error
                if (err.status == 420) {
                    console.log(err.status);
                    var mess = err.responseJSON.message;
                    alert(mess);
                }
            },
        });
    });
});
