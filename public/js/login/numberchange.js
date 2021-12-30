$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    $('#searchnumber').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var select = ($(document.activeElement).val());

        var name = [];
        var number = [];
        var department = [];
        var check = [];

        $("input:checkbox[name=innumber]:checked").each(function () {
            check.push($(this).val());
        });

        var count = check.length;

        for (let i = 0; i < count; i++) {
            name.push($("#name" + check[i]).val());
            number.push($("#number" + check[i]).val());
            department.push($("#department" + check[i]).val());
        }

        checked = $("input[type=checkbox]:checked").length;

        if (!checked) {
            alert(Lang.get('loginPageLang.nocheck'));
            return false;
        }

        if (select == "刪除" || select == "删除" || select == "Delete") {
            select = "刪除";
        }
        if (select == "更新" || select == "Update") {
            select = "更新";
        }

        $.ajax({
            type: 'POST',
            url: "numberchangeordel",
            data: {
                name: name,
                number: number,
                department: department,
                select: select,
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

                console.log(data);
                if (data.status == 201) {
                    var mess = Lang.get('loginPageLang.total') + (data.message) + Lang.get('loginPageLang.record') +
                        Lang.get('loginPageLang.pinf') + Lang.get('loginPageLang.change') + Lang.get('loginPageLang.success');
                    alert(mess);
                    window.location.href = 'number';

                } else {
                    var mess = Lang.get('loginPageLang.total') + (data.message) + Lang.get('loginPageLang.record') +
                        Lang.get('loginPageLang.pinf') + Lang.get('loginPageLang.delete') + Lang.get('loginPageLang.success');
                    alert(mess);
                    window.location.href = 'number';

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
