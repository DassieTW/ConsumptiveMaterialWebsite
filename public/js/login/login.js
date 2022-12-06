var imgclicked = false;
$(document).ready(function () {
  $("#fordatabase").on("click", function (event) {
    event.preventDefault();
    if (imgclicked === false) {
      $("#site").append(
        $("<option/>", {
          value: "Consumables management",
          text: "TEST database",
        })
      );
    }
    imgclicked = true;
  });

  $("#eye-button").on("click", function (event) {
    event.preventDefault();
    if ($("#show_hide_password input").attr("type") == "text") {
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
    } else if ($("#show_hide_password input").attr("type") == "password") {
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

    $("#password").focus();
  });

  $("#loginForm").on("submit", function (e) {
    e.preventDefault();
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    var name = $("#username").val();
    var pass = $("#password").val();
    var site = $("#site").children("option:selected").val();
    //TEST DATABASE
    //site = 'M2-TEST-1112';
    console.log(site); // test

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    $.ajax({
      type: "post",
      url: "/member/login",
      data: {
        username: name,
        password: pass,
        site: site,
      },
      dataType: "json", // let's set the expected response format
      beforeSend: function () {
        // console.log('sup, loading modal triggered !');
        // e.preventDefault();return false;  // test
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
        console.log(data.message); // test
        /*notyf.success({
                  message: "LogIn Success.",
                  duration: 3000,   //miliseconds, use 0 for infinite duration
                  ripple: true,
                  dismissible: true,
                  position: {
                    x: "right",
                    y: "bottom"
                  }
                });*/

        window.location.href = "/home";
      },
      error: function (err) {
        if (err.status == 422) {
          // when status code is 422, it's a validation issue
          // console.log(err.responseJSON.message); // test

          // you can loop through the errors object and show it to the user
          // console.warn(err.responseJSON.errors); // test
          // display errors on each form field
          $.each(err.responseJSON.errors, function (i, error) {
            var el = $(document).find('[name="' + i + '"]');
            console.log(el); // test
            el.addClass("is-invalid");
            el.focus();
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
        } // if error 422
        else if (err.status == 420) {
          // when login failed
          // Lang = new Lang();
          // console.log(err.responseJSON.message); // test
          // console.log(Lang.getLocale()); // test
          $("#username").addClass("is-invalid");
          $("#password").addClass("is-invalid");
          $("#username").focus();
          $("#password")
            .siblings(".input-group-text")
            .after(
              $(
                '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("auth.failed") +
                  "</strong></span>"
              )
            );
        } // else if error 418
      }, // error
    }); // end of ajax
  });
}); // on document ready

$(window).on("load", function () {
  // PAGE IS FULLY LOADED
  // FADE OUT YOUR OVERLAYING DIV
  $("body").loadingModal("hide");
  $("body").loadingModal("destroy");
});
