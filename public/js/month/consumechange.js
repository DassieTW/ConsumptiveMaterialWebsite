$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$('#consume').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var select = ($(document.activeElement).val());

    var client = [];
    var number = [];
    var production = [];
    var machine = [];
    var amount = [];
    var check = [];
    var jobnumber = $("#jobnumber").val();
    var email = $("#email").val();
    $("input:checkbox[name=innumber]:checked").each(function () {
        check.push($(this).val());
    });


    var count = check.length;

    for (let i = 0; i < count; i++) {
        client.push($("#client" + check[i]).val());
        number.push($("#number" + check[i]).val());
        production.push($("#production" + check[i]).val());
        machine.push($("#machine" + check[i]).val());
        amount.push($("#amount" + check[i]).val());
    }
    checked = $("input[type=checkbox]:checked").length;

    if (!checked) {
        alert(Lang.get('monthlyPRpageLang.nocheck'));
        return false;
    }

    if (select == "刪除" || select == "删除" || select == "Delete") {
        select = "刪除";
    }
    if (select == "更新" || select == "Update") {
        if (!jobnumber) {
            alert(Lang.get('monthlyPRpageLang.nopeople'));
            document.getElementById('jobnumber').classList.add("is-invalid");
            return false;
        } else if (!email) {
            alert(Lang.get('monthlyPRpageLang.noemail'));
            document.getElementById('email').classList.add("is-invalid");
            return false;
        }
        select = "更新";
    }

    $.ajax({
        type: 'POST',
        url: "consumechangeordelete",
        data: {
            client: client,
            number: number,
            production: production,
            machine: machine,
            amount: amount,
            select: select,
            jobnumber: jobnumber,
            email: email,
            count: count
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
            if (data.status == 201) {
                var mess = Lang.get('monthlyPRpageLang.total') + ' ' + (data.message) + ' ' + Lang.get('monthlyPRpageLang.record') +
                    ' ' + Lang.get('monthlyPRpageLang.isn') + ' ' + Lang.get('monthlyPRpageLang.consume') +
                    ' ' + Lang.get('monthlyPRpageLang.change') + ' ' + Lang.get('monthlyPRpageLang.submit') + ' ' + Lang.get('monthlyPRpageLang.success');
                alert(mess);

                window.location.href = "consume";

            } else {
                var mess = Lang.get('monthlyPRpageLang.total') + ' ' + (data.message) + ' ' + Lang.get('monthlyPRpageLang.record') +
                    ' ' + Lang.get('monthlyPRpageLang.isn') + ' ' + Lang.get('monthlyPRpageLang.consume') +
                    ' ' + Lang.get('monthlyPRpageLang.delete') + ' ' + Lang.get('monthlyPRpageLang.success');
                alert(mess);
                window.location.href = "consume";

            }

        },
        error: function (err) {
            console.log(err);

            var mess = err.responseJSON.message;
            alert(mess);

        },
    });
});
