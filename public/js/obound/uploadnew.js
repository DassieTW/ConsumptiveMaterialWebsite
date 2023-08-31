$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("#uploadnew").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var number = [];
    var name = [];
    var format = [];
    var row = [];

    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
      if (
        $("#data0" + i).val() !== null &&
        $("#data0" + i).val() !== undefined &&
        $("#data0" + i).val() !== " "
      ) {
        number.push($("#data0" + i).val());
        name.push($("#data1" + i).val());
        format.push($("#data2" + i).val());
        row.push(i.toString());
      } else {
        continue;
      }
    }

    var data = [];
    data.push(number);
    data.push(name);
    data.push(format);

    $.ajax({
      type: "POST",
      url: "insertuploadmaterial",
      data: {
        AllData: JSON.stringify(data),
        count: count,
        row: row,
      },
      // dataType: 'json', // let's set the expected response format
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
          Lang.get("oboundpageLang.total") +
          " : " +
          row.length +
          Lang.get("oboundpageLang.record") +
          Lang.get("oboundpageLang.matsdata") +
          " ， " +
          Lang.get("oboundpageLang.success") +
          Lang.get("oboundpageLang.new") +
          " : " +
          data.record +
          Lang.get("oboundpageLang.matsdata");

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

        var mess2 = Lang.get("oboundpageLang.yellowrepeat");

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
        // window.location.href = "/basic";
      },
      error: function (err) {
        console.log(err.status);
        //transaction error
        if (err.status === 423) {
          window.alert(err.responseJSON.message);
          console.log(err.responseJSON.message);
        }
      },
    });
  });
});
