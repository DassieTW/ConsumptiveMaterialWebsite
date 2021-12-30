$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    $('#searchusername').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();


        var username = [];
        var check = [];

        $("input:checkbox[name=innumber]:checked").each(function () {
            check.push($(this).val());
        });

        var count = check.length;

        for (let i = 0; i < count; i++) {
            username.push($("#username" + check[i]).val());

        }

        checked = $("input[type=checkbox]:checked").length;

        if (!checked) {
            alert(Lang.get('loginPageLang.nocheck'));
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "usernamechangeordel",
            data: {
                username: username,
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

                var mess = Lang.get('loginPageLang.total') + (data.message) + Lang.get('loginPageLang.record') +
                    Lang.get('loginPageLang.user') + Lang.get('loginPageLang.delete') + Lang.get('loginPageLang.success');
                alert(mess);
                window.location.href = 'username';

            },
            error: function (err) {
                console.log(err);

                var mess = err.responseJSON.message;
                alert(mess);

            },
        });
    });
});
