$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$('#monthsearch').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var check = [];
    checked = $("input[type=checkbox]:checked").length;

    if (!checked) {
        alert(Lang.get('monthlyPRpageLang.nocheck'));
        return false;
    }

    $("input:checkbox[name='innumber']:checked").each(function () {
        check.push($(this).val());
    });

    var count = check.length;
    var client = [];
    var machine = [];
    var production = [];

    for (let i = 0; i < count; i++) {
        client.push($("#client" + check[i]).val());
        machine.push($("#machine" + check[i]).val());
        production.push($("#production" + check[i]).val());
    }

    console.log(client);
    console.log(count);
    console.log(machine);
    console.log(production);

    $.ajax({
        type: 'POST',
        url: "monthdelete",
        data: {
            client: client,
            machine: machine,
            production: production,
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
                Lang.get('monthlyPRpageLang.month') + Lang.get('monthlyPRpageLang.delete') + Lang.get('monthlyPRpageLang.success');
            alert(mess);
            window.location.href = "importmonth";

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
