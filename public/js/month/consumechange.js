$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    function quickSearch() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = $("#numbersearch").val();
        //var isISN = $("#toggle-state").is(":checked");
        console.log(input); // test
        // filter = input.value;
        // Loop through all table rows, and hide those who don't match the search query
        $('.isnRows').each(function (i, obj) {
            txtValue = $(this).find("input[id^='number']").val();
            // console.log("now checking text : " + txtValue); // test
            if (txtValue.indexOf(input) > -1) {
                obj.style.display = "";

            } else {
                obj.style.display = "none";
            } // if else
        });
    } // quickSearch function


    $("#numbersearch").on('input', function (e) {
        e.preventDefault();
        quickSearch();
    });
    $('#consume').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var select = ($(document.activeElement).val());

        if (select == "返回" || select == "return") {
            window.location.href = "consume";
        }
        var client = [];
        var number = [];
        var production = [];
        var machine = [];
        var amount = [];
        var check = [];
        var jobnumber = $("#jobnumber").val();
        var email = $("#email").val();
        email = email + '@pegatroncorp.com';
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
            if (select == "刪除" || select == "删除" || select == "Delete" || select == "更新" || select == "Update")

                notyf.open({
                    type: 'warning',
                    message: Lang.get('monthlyPRpageLang.nocheck'),
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

        if (select == "刪除" || select == "删除" || select == "Delete") {
            select = "刪除";
        }
        if (select == "更新" || select == "Update") {
            if (!jobnumber) {
                // alert(Lang.get('monthlyPRpageLang.nopeople'));
                notyf.open({
                    type: 'warning',
                    message: Lang.get('monthlyPRpageLang.nopeople'),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });
                document.getElementById('jobnumber').classList.add("is-invalid");
                return false;
            } else if (!email) {
                // alert(Lang.get('monthlyPRpageLang.noemail'));
                notyf.open({
                    type: 'warning',
                    message: Lang.get('monthlyPRpageLang.noemail'),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });
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
});
