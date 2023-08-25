$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("#eye-button").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password input").attr("type") === "text") {
      $("#show_hide_password input").attr("type", "password");
      document
        .getElementById("eye-slash-fill")
        .setAttribute(
          "d",
          "m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"
        );
      document
        .getElementById("eye-slash-fill2")
        .setAttribute(
          "d",
          "M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"
        );
    } else if ($("#show_hide_password input").attr("type") === "password") {
      $("#show_hide_password input").attr("type", "text");
      document
        .getElementById("eye-slash-fill")
        .setAttribute("d", "M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z");
      document
        .getElementById("eye-slash-fill2")
        .setAttribute(
          "d",
          "M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"
        );
    } // if else if

    $("#password").trigger("focus");
  });

  $("#eye-button1").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password1 input").attr("type") == "text") {
      $("#show_hide_password1 input").attr("type", "password");
      document
        .getElementById("eye-slash-fill3")
        .setAttribute(
          "d",
          "m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"
        );
      document
        .getElementById("eye-slash-fill4")
        .setAttribute(
          "d",
          "M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"
        );
    } else if ($("#show_hide_password1 input").attr("type") == "password") {
      $("#show_hide_password1 input").attr("type", "text");
      document
        .getElementById("eye-slash-fill3")
        .setAttribute("d", "M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z");
      document
        .getElementById("eye-slash-fill4")
        .setAttribute(
          "d",
          "M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"
        );
    } // if else if

    $("#newpassword").trigger("focus");
  });

  $("#eye-button2").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password2 input").attr("type") == "text") {
      $("#show_hide_password2 input").attr("type", "password");
      document
        .getElementById("eye-slash-fill5")
        .setAttribute(
          "d",
          "m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z"
        );
      document
        .getElementById("eye-slash-fill6")
        .setAttribute(
          "d",
          "M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z"
        );
    } else if ($("#show_hide_password2 input").attr("type") === "password") {
      $("#show_hide_password2 input").attr("type", "text");
      document
        .getElementById("eye-slash-fill5")
        .setAttribute("d", "M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z");
      document
        .getElementById("eye-slash-fill6")
        .setAttribute(
          "d",
          "M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"
        );
    } // if else if

    $("#surepassword").trigger("focus");
  });

  $("#changepassword").on("submit", function (e) {
    e.preventDefault();
    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();
    var password = $("#password").val();
    var newpassword = $("#newpassword").val();
    var surepassword = $("#surepassword").val();
    $.ajax({
      type: "POST",
      url: "change",
      data: {
        password: password,
        newpassword: newpassword,
        surepassword: surepassword,
      },
      dataType: "json", // expected respose datatype from server
      beforeSend: function () {
        // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
        $("body").loadingModal({
          text: "Loading...",
          animation: "circle",
        });
      },
      complete: function () {
        $("body").loadingModal("hide");
        $("body").loadingModal("destroy");
      },
      success: function (data) {
        var mess =
          Lang.get("loginPageLang.change") +
          Lang.get("oboundpageLang.success") +
          "，" +
          Lang.get("loginPageLang.againlogin");

        notyf.open({
          type: "success",
          message: mess,
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });

        setTimeout(() => (window.location.href = "login"), 1500);
      },
      error: function (err) {
        // 舊密碼輸入錯誤
        if (err.status === 420) {
          document.getElementById("password").classList.add("is-invalid");
          document.getElementById("password").value = "";
          $("#password")
            .siblings(".input-group-text")
            .after(
              $(
                '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("loginPageLang.errorpassword") +
                  "</strong></span>"
              )
            );
        } // if
        // 密碼並不相符
        else if (err.status === 421) {
          document.getElementById("newpassword").classList.add("is-invalid");
          document.getElementById("newpassword").value = "";
          document.getElementById("surepassword").classList.add("is-invalid");
          document.getElementById("surepassword").value = "";
          $("#newpassword")
            .siblings(".input-group-text")
            .after(
              $(
                '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("loginPageLang.errorpassword2") +
                  "</strong></span>"
              )
            );
          $("#surepassword")
            .siblings(".input-group-text")
            .after(
              $(
                '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("loginPageLang.errorpassword2") +
                  "</strong></span>"
              )
            );
        } // else if
        else if (err.status === 422) {
          // when status code is 422, it's a validation issue
          // console.log(err.responseJSON.message); // test

          // you can loop through the errors object and show it to the user
          // console.warn(err.responseJSON.errors); // test
          // display errors on each form field
          $.each(err.responseJSON.errors, function (i, error) {
            var el = $(document).find('[name="' + i + '"]');
            // console.log(el); // test
            el.addClass("is-invalid");
            if (el.siblings(".input-group-text").length > 0) {
              el.siblings(".input-group-text").after(
                $(
                  '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                    error[0] +
                    "</strong></span>"
                )
              );
            } // if
            else {
              el.after(
                $(
                  '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                    error[0] +
                    "</strong></span>"
                )
              );
            } // if else
          });
        } // else if
        else {
          console.log(err.status); // print out other errors
        } // else
      },
    });
  });

  $("#changeEmail").on("submit", function (e) {
    e.preventDefault();
    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();
    let emailTail = $("#emailTail option:selected").text();
    var newEmail = $("#newMail").val();
    if (newEmail === "" || newEmail === null) {
      // do nothing
    } // if
    else {
      newEmail = $("#newMail").val() + emailTail;
    } // else

    $("#newMail").val(""); // clean up after input
    var oldEmail = $("#oldMail").val();
    // console.log( oldEmail + "+++" + newEmail + "+++"); // test
    $.ajax({
      type: "POST",
      url: "change",
      data: {
        newMail: newEmail,
        oldMail: oldEmail,
      },
      dataType: "json", // expected respose datatype from server
      beforeSend: function () {
        // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
        $("body").loadingModal({
          text: "Loading...",
          animation: "circle",
        });
      },
      complete: function () {
        $("body").loadingModal("hide");
        $("body").loadingModal("destroy");
      },
      success: function (data) {
        if (newEmail !== "" && newEmail !== null) {
          $("#oldMail").val(newEmail);
        } // if
        else {
          $("#oldMail").val("");
        } // if

        notyf.success({
          message: Lang.get("loginPageLang.success"),
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
      },
      error: function (err) {
        if (err.status === 422) {
          // when status code is 422, it's a validation issue
          // console.log(err.responseJSON.message); // test

          // you can loop through the errors object and show it to the user
          // console.warn(err.responseJSON.errors); // test
          // display errors on each form field
          $.each(err.responseJSON.errors, function (i, error) {
            var el = $(document).find('[name="' + i + '"]');
            // console.log(i); // test
            el.addClass("is-invalid");
            if (el.siblings(".input-group-text").length > 0) {
              el.siblings(".input-group-text").after(
                $(
                  '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                    error[0] +
                    "</strong></span>"
                )
              );
            } // if
            else {
              el.after(
                $(
                  '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                    error[0] +
                    "</strong></span>"
                )
              );
            } // if else
          });
        } // if
        else {
          console.log(err.status); // print out other errors
          console.log(err.responseJSON.message); // print out other errors
        } // else
      },
    });
  });
});
