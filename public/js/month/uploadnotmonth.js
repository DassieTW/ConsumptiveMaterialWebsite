$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});
var count = $("#count").val();

$(function () {
  $("#uploadnotmonth").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").hide();

    var number = [];
    var desc = [];
    var amount = [];
    var row = [];

    for (let i = 0; i < count; i++) {
      if (
        $("#number" + i).val() !== null &&
        $("#number" + i).val() !== "" &&
        $("#number" + i).val() !== undefined
      ) {
        number.push($("#number" + i).val());
        amount.push($("#amount" + i).val());
        desc.push($("#desc" + i).val());
        row.push(i.toString());
      }
    }
    if (number.length === 0) {
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
    }

    count = number.length;
    console.log(count);
    $.ajax({
      type: "POST",
      url: "notmonthsubmit",
      data: {
        amount: amount,
        number: number,
        desc: desc,
        count: count,
        row: row,
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
        var mess =
          Lang.get("monthlyPRpageLang.total") +
          " : " +
          data.record +
          " " +
          Lang.get("monthlyPRpageLang.record") +
          Lang.get("monthlyPRpageLang.notmonth") +
          Lang.get("monthlyPRpageLang.add") +
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

        for (let i = 0; i < row.length; i++) {
          var same = row.filter(function (v) {
            return data.check.indexOf(v) > -1;
          });
        }
        for (let i = 0; i < same.length; i++) {
          console.log(same[i]);
          $("#row" + same[i]).remove();
          count = count - 1;
        }
      },

      error: function (err) {
        console.log(err);
        //transaction error

        alert(err.responseJSON.message);
        window.location.reload();
      },
    });
  });
});
