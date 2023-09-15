$(function () {
  $("#email").on("change", function (event) {
    $("#emailTail").text($(this).val());
  });
  var count = $("#count").val();
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $("#uploadconsume").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var row = [];
    var number = [];
    var consume = [];
    var number90 = [];

    if ($("#email").val() === null) {
      notyf.open({
        type: "warning",
        message: Lang.get("monthlyPRpageLang.noemail"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
          x: "right",
          y: "bottom",
        },
      });
      document.getElementById("email").classList.add("is-invalid");
      document.getElementById("email").classList.add("is-invalid");
      document.getElementById("email").focus();
      return false;
    } else {
      var email = $("#email option:selected").text();
      console.log(email);
    }
    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
      if ($("#number" + i).val() !== null && $("#number" + i).val() !== "") {
        number.push($("#number" + i).val());
        consume.push($("#amount" + i).val());
        number90.push($("#90isn" + i).val());
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

    $.ajax({
      type: "POST",
      url: "consumenewsubmit",
      data: {
        number: number,
        number90: number90,
        consume: consume,
        email: email,
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
          row.length +
          Lang.get("monthlyPRpageLang.record") +
          Lang.get("monthlyPRpageLang.data") +
          " ， " +
          Lang.get("monthlyPRpageLang.success") +
          Lang.get("monthlyPRpageLang.new") +
          " : " +
          data.record +
          Lang.get("monthlyPRpageLang.record") +
          Lang.get("monthlyPRpageLang.consume");
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

        var mess2 = Lang.get("monthlyPRpageLang.yellowrepeat");

        setTimeout(
          () =>
            notyf.open({
              type: "info",
              message: mess2,
              duration: 3000, //miliseconds, use 0 for infinite duration
              ripple: true,
              dismissible: true,
              position: {
                x: "right",
                y: "bottom",
              },
            }),
          1000
        );

        for (let i = 0; i < row.length; i++) {
          var same = row.filter(function (v) {
            return data.check.indexOf(v) > -1;
          });
          var diff = row.filter(function (v) {
            return data.check.indexOf(v) == -1;
          });
        }
        for (let i = 0; i < same.length; i++) {
          $("#row" + same[i]).remove();
        }
        for (let i = 0; i < diff.length; i++) {
          document.getElementById("row" + diff[i]).style.backgroundColor =
            "yellow";
        }
        // $("#consumebody").hide();
      },
      error: function (err) {
        //transaction error
        if (err.status === 421) {
          alert(err.responseJSON.message);
          window.location.reload();
        }
      },
    });
  });
});
