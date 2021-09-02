
document.getElementById("message").style.display = "none";
document.getElementById("message").style.color = "red";
document.getElementById("message2").style.display = "none";
document.getElementById("message2").style.color = "red";


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

  $("#eye-button1").on('click', function (event) {
    event.preventDefault();
    if ($('#show_hide_password1 input').attr("type") == "text") {
      $('#show_hide_password1 input').attr('type', 'password');
      document.getElementById('eye-slash-fill3').setAttribute('d', 'm10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z');
      document.getElementById('eye-slash-fill4').setAttribute('d', 'M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z');
    } else if ($('#show_hide_password1 input').attr("type") == "password") {
      $('#show_hide_password1 input').attr('type', 'text');
      document.getElementById('eye-slash-fill3').setAttribute('d', 'M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z');
      document.getElementById('eye-slash-fill4').setAttribute('d', 'M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z');
    } // if else if

    $("#newpassword").focus();
  });

  $("#eye-button2").on('click', function (event) {
    event.preventDefault();
    if ($('#show_hide_password2 input').attr("type") == "text") {
      $('#show_hide_password2 input').attr('type', 'password');
      document.getElementById('eye-slash-fill5').setAttribute('d', 'm10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z');
      document.getElementById('eye-slash-fill6').setAttribute('d', 'M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z');
    } else if ($('#show_hide_password2 input').attr("type") == "password") {
      $('#show_hide_password2 input').attr('type', 'text');
      document.getElementById('eye-slash-fill5').setAttribute('d', 'M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z');
      document.getElementById('eye-slash-fill6').setAttribute('d', 'M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z');
    } // if else if

    $("#surepassword").focus();
  });

  $('#changepassword').on('submit', function (e) {
    e.preventDefault();
    var password = $("#password").val();
    var newpassword = $("#newpassword").val();
    var surepassword = $("#surepassword").val();
    $.ajax({
      type: 'POST',
      url: "change",
      data: { password: password, newpassword: newpassword, surepassword: surepassword },
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
        console.log(data);
        var myObj = JSON.parse(data);
        console.log(myObj);
        if (myObj.boolean === true && myObj.passbool === true) {
          var mess = Lang.get('loginPageLang.change') + Lang.get('oboundpageLang.success') + '，' +
            Lang.get('loginPageLang.againlogin');
          alert(mess);
          //alert('更新成功，請重新登入。');
          window.location.href = "login";
        }
        else if (myObj.boolean === true && myObj.passbool === false) {
          document.getElementById("message").style.display = "block";
          document.getElementById('password').style.borderColor = "red";
          document.getElementById('password').value = '';
        }
        else {
          document.getElementById("message2").style.display = "block";
          document.getElementById('newpassword').style.borderColor = "red";
          document.getElementById('newpassword').value = '';
          document.getElementById('surepassword').style.borderColor = "red";
          document.getElementById('surepassword').value = '';
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
  });
});
