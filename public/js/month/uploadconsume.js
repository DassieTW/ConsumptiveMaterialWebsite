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
    var client = [];
    var number = [];
    var production = [];
    var machine = [];
    var consume = [];
    var number90 = [];

    var jobnumber = $("#jobnumber").val();
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
      if (
        $("#datag" + i).val() !== null &&
        $("#datag" + i).val() !== undefined &&
        $("#datag" + i).val() !== " "
      ) {
        client.push($("#datag" + i).val());
        machine.push($("#datah" + i).val());
        production.push($("#datai" + i).val());
        number.push($("#dataa" + i).val());
        consume.push($("#datac" + i).val());
        number90.push($("#dataj" + i).val());
        row.push(i.toString());
      } else {
        continue;
      }
    }

    $.ajax({
      type: "POST",
      url: "consumenewsubmit",
      data: {
        client: client,
        number: number,
        number90: number90,
        production: production,
        machine: machine,
        consume: consume,
        jobnumber: jobnumber,
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
