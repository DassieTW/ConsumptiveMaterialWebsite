$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $("#eye-button").on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_password input').attr("type") == "text") {
            $('#show_hide_password input').attr('type', 'password');
            document.getElementById('eye-slash-fill').setAttribute('d', 'm10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z');
            document.getElementById('eye-slash-fill2').setAttribute('d', 'M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z');
        } else if ($('#show_hide_password input').attr("type") == "password") {
            $('#show_hide_password input').attr('type', 'text');
            document.getElementById('eye-slash-fill').setAttribute('d', 'M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z');
            document.getElementById('eye-slash-fill2').setAttribute('d', 'M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z');
        } // if else if

        $("#password").focus();
    });

    $("#eye-button2").on('click', function (event) {
        event.preventDefault();
        if ($('#show_hide_password2 input').attr("type") == "text") {
            $('#show_hide_password2 input').attr('type', 'password');
            document.getElementById('eye-slash-fill3').setAttribute('d', 'm10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z');
            document.getElementById('eye-slash-fill4').setAttribute('d', 'M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z');
        } else if ($('#show_hide_password2 input').attr("type") == "password") {
            $('#show_hide_password2 input').attr('type', 'text');
            document.getElementById('eye-slash-fill3').setAttribute('d', 'M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z');
            document.getElementById('eye-slash-fill4').setAttribute('d', 'M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z');
        } // if else if

        $("#password2").focus();
    });

    $('#registerform').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        var username = $("#username").val();
        var password = $("#password").val();
        var surepassword = $("#password2").val();
        var priority = $("#priority").val();
        var name = $("#name").val();
        var email = $("#email").val();
        var department = $("#department").val();
        var profilePic = $('input[name=flexRadioDefault]:checked', '#registerform').val();

        if (password !== surepassword) {
            var mess = Lang.get("loginPageLang.errorpassword2");
            alert(mess);
            $("#password").addClass("is-invalid");
            $("#password2").addClass("is-invalid");
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "register",
            data: {
                username: username,
                password: password,
                surepassword: surepassword,
                priority: priority,
                name: name,
                email: email,
                department: department,
                profilePic: profilePic,
            },
            beforeSend: function () {
                // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
                $('body').loadingModal({
                    text: 'Loading...',
                    animation: 'circle'
                });
            },
            complete: function () {
                $("body").loadingModal("hide");
                $('body').loadingModal('destroy');
            },

            success: function (data) {
                var mess = Lang.get("loginPageLang.new") + Lang.get("loginPageLang.success") +
                    Lang.get("loginPageLang.againlogin");
                alert(mess);
                window.location.href='login';
            },
            error: function (err) {
                if (err.status == 420) {
                    var mess = Lang.get("loginPageLang.usernamerepeat");
                    alert(mess);
                    $("#username").addClass("is-invalid");
                    return false;
                }
            },
        });
    });
});
