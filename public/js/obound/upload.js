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
    var client = [];
    var number = [];
    var name = [];
    var format = [];
    var amount = [];
    var bound = [];
    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
      client.push($("#data0" + i).val());
      number.push($("#data1" + i).val());
      name.push($("#data2" + i).val());
      format.push($("#data3" + i).val());
      amount.push($("#data4" + i).val());
      bound.push($("#data5" + i).val());
    }

    data.push(client);
    data.push(number);
    data.push(name);
    data.push(format);
    data.push(amount);
    data.push(bound);
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
          Lang.get("oboundpageLang.total") +
          " " +
          data.message +
          " " +
          Lang.get("oboundpageLang.record") +
          Lang.get("oboundpageLang.stockupload") +
          Lang.get("oboundpageLang.success");
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
