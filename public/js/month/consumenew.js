$(document).ready(function () {
    $("input").change(function () {
        var nowmps = $("#nowmps").val();
        var amount = $("#amount").val();
        var nowday = $("#nowday").val();
        var nextmps = $("#nextmps").val();
        var nextday = $("#nextday").val();
        var lt = $("#lt").val();
        var nowneed = (nowmps * amount) / nowday;
        var nextneed = (nextmps * amount) / nextday;
        var safe = nextneed * lt;
        nowneed = nowneed.toFixed(7);
        nextneed = nextneed.toFixed(7);
        safe = safe.toFixed(7);
        $('#nowneed').val(nowneed);
        $('#nextneed').val(nextneed);
        $('#safe').val(safe);
    });
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#consumenew').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var number = $("#number").val();
    var client = $("#client").val();
    var machine = $("#machine").val();
    var production = $("#production").val();
    var amount = $("#amount").val();
    var jobnumber = $("#jobnumber").val();
    var email = $("#email").val();

    $.ajax({
        type: 'POST',
        url: "consumenewsubmit",
        data: {
            number: number,
            client: client,
            machine: machine,
            production: production,
            amount: amount,
            jobnumber: jobnumber,
            email: email
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
        },
        success: function (data) {

            var mess = Lang.get('monthlyPRpageLang.isn') + Lang.get('monthlyPRpageLang.consume') +
                Lang.get('monthlyPRpageLang.new') + Lang.get('monthlyPRpageLang.submit') + Lang.get('monthlyPRpageLang.success');
            alert(mess);

            $("#consumebody").hide();
            $('#url').append(' URL : ' + '<a>http://127.0.0.1/month/testconsume?' + data.database + '</a>');

        },
        error: function (err) {
            //repeat
            if (err.status == 420) {
                var mess = Lang.get('monthlyPRpageLang.repeat');
                alert(mess);
                return false;

            }
            //transaction error
            else if (err.status == 421) {
                console.log(err.status);
            }
        },
    });
});
