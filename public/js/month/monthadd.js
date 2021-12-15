
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {

    $('#monthadd').on('submit', function (e) {
        e.preventDefault();


        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var count = $("#count").val();
        var client = [];
        var machine = [];
        var production = [];
        var nextmps = [];
        var nextday = [];
        var nowmps = [];
        var nowday = [];

        for (let i = 0; i < count; i++) {

            client.push($("#client" + i).text());
            machine.push($("#machine" + i).text());
            production.push($("#production" + i).text());
            nextmps.push(($("#nextmps" + i).val()));
            nextday.push(($("#nextday" + i).val()));
            nowmps.push(($("#nowmps" + i).val()));
            nowday.push(($("#nowday" + i).val()));
        }

        $.ajax({
            type: 'POST',
            url: "monthsubmit",
            data: {
                client: client,
                machine: machine,
                production: production,
                nextmps: nextmps,
                nextday: nextday,
                nowmps: nowmps,
                nowday: nowday,
                count : count,
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
                    Lang.get('templateWords.monthly') + Lang.get('monthlyPRpageLang.new') + Lang.get('monthlyPRpageLang.success');
                alert(mess);
                window.location.href = "importmonth";
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
});
