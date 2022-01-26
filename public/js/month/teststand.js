$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    $('#standcheck').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();


        var client = [];
        var name = [];
        var number = [];
        var production = [];
        var machine = [];
        var nowpeople = [];
        var nowline = [];
        var nowclass = [];
        var nowuse = [];
        var nowchange = [];
        var nextpeople = [];
        var nextline = [];
        var nextclass = [];
        var nextuse = [];
        var nextchange = [];
        var check = [];
        var jobnumber = $("#jobnumber").val();
        var email = $("#email").val();
        var count = $("#count").val();
        var sender = $("#sender").val();

        var data = [];

        for (let i = 0; i < count; i++) {
            client.push($("#client" + i).val());
            name.push($("#name" + i).val());
            number.push($("#number" + i).val());
            production.push($("#production" + i).val());
            machine.push($("#machine" + i).val());
            nowpeople.push($("#nowpeople" + i).val());
            nowline.push($("#nowline" + i).val());
            nowclass.push($("#nowclass" + i).val());
            nowuse.push($("#nowuse" + i).val());
            nowchange.push($("#nowchange" + i).val());
            nextpeople.push($("#nextpeople" + i).val());
            nextline.push($("#nextline" + i).val());
            nextclass.push($("#nextclass" + i).val());
            nextuse.push($("#nextuse" + i).val());
            nextchange.push($("#nextchange" + i).val());
            check.push($("#check" + i).prop('checked'));
        }

        if (count == undefined) {
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
        var mess = Lang.get('monthlyPRpageLang.total') + ' ' + count + ' ' +
            Lang.get('monthlyPRpageLang.record') + ' ' + Lang.get('monthlyPRpageLang.stand');

        var sure = window.confirm(mess);

        data.push(number);
        data.push(name);
        data.push(client);
        data.push(machine);
        data.push(production);
        data.push(nowpeople);
        data.push(nowline);
        data.push(nowclass);
        data.push(nowuse);
        data.push(nowchange);
        data.push(nextpeople);
        data.push(nextline);
        data.push(nextclass);
        data.push(nextuse);
        data.push(nextchange);
        data.push(check);

        if (sure !== true) {
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: "teststand",
                data: {
                    AllData: JSON.stringify(data),
                    jobnumber: jobnumber,
                    email: email,
                    count: count,
                    sender: sender,
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
                    console.log(data);
                    var mess = Lang.get('monthlyPRpageLang.total') + (data.message) + Lang.get('monthlyPRpageLang.record') +
                        Lang.get('monthlyPRpageLang.success');
                    alert(mess);
                    window.location.reload();

                },
                error: function (err) {
                    console.log(err.status);
                    var mess = err.responseJSON.message;
                    alert(mess);
                }
            });
        }
    });
});
