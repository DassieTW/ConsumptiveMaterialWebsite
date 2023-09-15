$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("#monthadd").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var number = [];
    var number90 = [];
    var nextmps = [];
    var nextday = [];
    var nowmps = [];
    var nowday = [];

    var count = 1;
    for (let i = 0; i < count; i++) {
      number.push($("#number").text());
      number90.push($("#number90").text());
      nextmps.push($("#nextmps").val());
      nextday.push($("#nextday").val());
      nowmps.push($("#nowmps").val());
      nowday.push($("#nowday").val());
    }

    $.ajax({
      type: "POST",
      url: "monthsubmit",
      data: {
        number: number,
        number90: number90,
        nextmps: nextmps,
        nextday: nextday,
        nowmps: nowmps,
        nowday: nowday,
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
