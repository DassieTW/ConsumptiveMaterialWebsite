$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("#uploadmonth").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var client = [];
    var machine = [];
    var production = [];
    var nowmps = [];
    var nowday = [];
    var nextmps = [];
    var nextday = [];
    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
      client.push($("#data0" + i).text());
      machine.push($("#data1" + i).text());
      production.push($("#data2" + i).text());
      nowmps.push($("#data3" + i).val());
      nowday.push($("#data4" + i).val());
      nextmps.push($("#data5" + i).val());
      nextday.push($("#data6" + i).val());
    }

    $.ajax({
      type: "POST",
      url: "monthsubmit",
      data: {
        client: client,
        machine: machine,
        production: production,
        nowmps: nowmps,
        nowday: nowday,
        nextmps: nextmps,
        nextday: nextday,
        count: count,
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
          Lang.get("templateWords.monthly") +
          Lang.get("monthlyPRpageLang.new") +
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

        setTimeout(() => (window.location.href = "importmonth"), 1500);
        //window.location.href = "member.newok";
      },
      error: function (err) {
        //transaction error
        if (err.status === 421) {
          console.log(err.status);
          alert(err.responseJSON.message);
          window.location.reload();
        }
      },
    });
  });
});
