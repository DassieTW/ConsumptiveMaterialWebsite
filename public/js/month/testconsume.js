$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $(".checkbutton").on("change", function () {
    var count = $("#count").val();
    for (let i = 0; i < count; i++) {
      if ($("#check" + i).prop("checked")) {
        $("#remark" + i).prop("required", false);
      } else {
        $("#remark" + i).prop("required", true);
      }
    }
  });
  $("#consumecheck").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var name = [];
    var number = [];
    var number90 = [];
    var amount = [];
    var check = [];
    var reason = [];
    // var jobnumber = $("#jobnumber").val();
    var email = $("#email").val();
    var count = $("#count").val();
    var sender = $("#sender").val();
    var database = $("#database").val();

    var data = [];

    for (let i = 0; i < count; i++) {
      name.push($("#name" + i).val());
      number.push($("#number" + i).val());
      number90.push($("#number90" + i).val());
      amount.push($("#amount" + i).html());
      check.push($("#check" + i).prop("checked"));
      reason.push($("#remark" + i).val());
    } // for

    if (count === undefined) {
      notyf.open({
        type: "warning",
        message: Lang.get("monthlyPRpageLang.nodata"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
          x: "right",
          y: "bottom",
        },
      });
      return false;
    } // if

    var mess =
      Lang.get("monthlyPRpageLang.total") +
      " " +
      count +
      " " +
      Lang.get("monthlyPRpageLang.record") +
      " " +
      Lang.get("monthlyPRpageLang.consume");

    var sure = window.confirm(mess);

    data.push(number);
    data.push(name);
    data.push(amount);
    data.push(check);
    data.push(reason);
    data.push(number90);

    if (sure !== true) {
      return false;
    } else {
      $.ajax({
        type: "POST",
        url: "testconsume_submit",
        data: {
          AllData: JSON.stringify(data),
          // jobnumber: jobnumber,
          email: email,
          count: count,
          sender: sender,
          database: database,
        },
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
          console.log(data.message);
          var mess =
            Lang.get("monthlyPRpageLang.total") +
            " " +
            data.message +
            " " +
            Lang.get("monthlyPRpageLang.record") +
            " " +
            Lang.get("monthlyPRpageLang.success");

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

          setTimeout(() => window.location.reload(), 1500);
        },
        error: function (err) {
          //transaction error
          console.log(err.status);
          var mess = err.responseJSON.message;
          alert(mess);
        },
      });
    }
  });
});
