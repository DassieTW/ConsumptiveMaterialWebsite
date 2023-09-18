$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("#uploadinventory").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var data = [];
    var number = [];
    var amount = [];
    var position = [];
    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
      number.push($("#data1" + i).val());
      amount.push($("#data2" + i).val());
      position.push($("#data3" + i).val());
    }

    data.push(number);
    data.push(amount);
    data.push(position);
    $.ajax({
      type: "POST",
      url: "insertuploadinventory",
      data: {
        AllData: JSON.stringify(data),
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
        console.log(number);
        var mess =
          Lang.get("inboundpageLang.total") +
          " " +
          data.message +
          " " +
          Lang.get("inboundpageLang.record") +
          " " +
          Lang.get("inboundpageLang.stockupload") +
          " " +
          Lang.get("inboundpageLang.success");
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

        setTimeout(() => (window.location.href = "upload"), 1500);
      },
      error: function (err) {
        console.log(err.status);

        var mess = err.responseJSON.message;
        window.alert(mess);
        window.location.reload();
      },
    });
  });
});
