$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$('#srmsubmit').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var count = $("#count").val();
    var client = [];
    var number = [];
    var sxbamount = [];
    var buyamount = [];
    var srmnumber = [];
    var sxbnumber = [];
    for (let i = 0; i < count; i++) {
        client.push($("#client" + i).val());
        number.push($("#number" + i).val());
        sxbamount.push($("#sxbamount" + i).val());
        buyamount.push($("#buyamount" + i).val());
        srmnumber.push($("#srmnumber" + i).val());
        sxbnumber.push($("#sxbnumber" + i).val());
    }
    for (let i = 0; i < count; i++) {
        if (parseInt(sxbamount[i]) > parseInt(buyamount[i])) {
            row = i + 1;
            mess = Lang.get('monthlyPRpageLang.sxbamounterr') + ' ' + Lang.get('monthlyPRpageLang.row') +
                ' : ' + row;
            alert(mess);
            return false;
        } else {
            continue;
        }
    }

    $.ajax({
        type: 'POST',
        url: "srmsubmit",
        data: {
            client: client,
            number: number,
            sxbamount: sxbamount,
            srmnumber: srmnumber,
            sxbnumber :sxbnumber,
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
                Lang.get('monthlyPRpageLang.SRM') + Lang.get('monthlyPRpageLang.submit') + Lang.get('monthlyPRpageLang.success');
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
