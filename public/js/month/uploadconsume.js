var count = $("#count").val();
for (var i = 0; i < count; i++) {
    var nowmps = $("#dataj" + i).val();
    var nowday = $("#datak" + i).val();
    var amount = $("#datac" + i).val();
    var nextmps = $("#datal" + i).val();
    var nextday = $("#datam" + i).val();
    var lt = $("#datab" + i).val();
    var nowneed = (nowmps * amount) / nowday;
    var nextneed = (nextmps * amount) / nextday;
    var safe = nextneed * lt;
    nowneed = nowneed.toFixed(7);
    nextneed = nextneed.toFixed(7);
    safe = safe.toFixed(7);
    $('#datad' + i).val(nowneed);
    $('#datae' + i).val(nextneed);
    $('#dataf' + i).val(safe);
}
$(document).ready(function () {
    $("input").change(function () {

        for (var i = 0; i < count; i++) {
            var nowmps = $("#dataj" + i).val();
            var nowday = $("#datak" + i).val();
            var amount = $("#datac" + i).val();
            var nextmps = $("#datal" + i).val();
            var nextday = $("#datam" + i).val();
            var lt = $("#datab" + i).val();
            var nowneed = (nowmps * amount) / nowday;
            var nextneed = (nextmps * amount) / nextday;
            var safe = nextneed * lt;
            nowneed = nowneed.toFixed(7);
            nextneed = nextneed.toFixed(7);
            safe = safe.toFixed(7);
            $('#datad' + i).val(nowneed);
            $('#datae' + i).val(nextneed);
            $('#dataf' + i).val(safe);
        }

    });


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    $('#uploadconsume').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var row = [];
        var client = [];
        var number = [];
        var production = [];
        var machine = [];
        var consume = [];
        var jobnumber = $("#jobnumber").val();
        var email = $("#email").val();

        var count = $("#count").val();

        for (let i = 0; i < count; i++) {
            if ($("#datag" + i).val() !== null && $("#datag" + i).val() !== undefined && $("#datag" + i).val() !== ' ') {
                client.push($("#datag" + i).val());
                machine.push($("#datah" + i).val());
                production.push($("#datai" + i).val());
                number.push($("#dataa" + i).val());
                consume.push($("#datac" + i).val());
                row.push(i.toString());
            } else {
                continue;
            }

        }

        $.ajax({
            type: 'POST',
            url: "consumenewsubmit",
            data: {
                client: client,
                number: number,
                production: production,
                machine: machine,
                consume: consume,
                jobnumber: jobnumber,
                email: email,
                count: count,
                row: row,
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

                var mess = Lang.get('monthlyPRpageLang.total') + ' : ' + row.length + Lang.get('monthlyPRpageLang.record') +
                    Lang.get('monthlyPRpageLang.data') + ' ， ' + Lang.get('monthlyPRpageLang.success') + Lang.get('monthlyPRpageLang.new') +
                    ' : ' + data.record + Lang.get('monthlyPRpageLang.record') + Lang.get('monthlyPRpageLang.consume');
                alert(mess);

                var mess2 = Lang.get('monthlyPRpageLang.yellowrepeat');

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
                // $("#consumebody").hide();

                // $('#url').append(' URL : ' + '<a>http://127.0.0.1/month/testconsume?' + data.database + '</a>');

            },
            error: function (err) {
                //transaction error
                if (err.status == 421) {
                    alert(err.responseJSON.message);
                    window.location.reload();
                }
            },
        });
    });
});
