$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("#uploadpeople").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var count = $("#count").val();
    var number = [];
    var name = [];
    var department = [];
    var row = [];
    for (let i = 0; i < count; i++) {
      if (
        $("#data0" + i).val() !== null &&
        $("#data0" + i).val() !== undefined &&
        $("#data0" + i).val() !== " "
      ) {
        number.push($("#data0" + i).val());
        name.push($("#data1" + i).val());
        department.push($("#data2" + i).val());
        row.push(i.toString());
      } else {
        continue;
      }
    }

    for (let i = 0; i < count; i++) {
      if (number[i] !== undefined) {
        if (number[i].length !== 9) {
          var errorrow = i + 1;
          var mess =
            Lang.get("loginPageLang.row") +
            " " +
            errorrow +
            " " +
            Lang.get("loginPageLang.joblength");
          alert(mess);
          $("#data0" + i).addClass("is-invalid");
          return false;
        } else {
          continue;
        }
      }
    }
    $.ajax({
      type: "POST",
      url: "insertuploadpeople",
      data: {
        number: number,
        name: name,
        department: department,
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
          Lang.get("loginPageLang.total") +
          " : " +
          row.length +
          Lang.get("loginPageLang.record") +
          Lang.get("loginPageLang.data") +
          " ， " +
          Lang.get("loginPageLang.success") +
          Lang.get("loginPageLang.upload1") +
          " : " +
          data.record +
          " " +
          Lang.get("loginPageLang.pinf");

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

        var mess2 = Lang.get("loginPageLang.yellowrepeat");

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
      },
      error: function (err) {
        console.log(err);
        //repeat
        if (err.status === 420) {
          var mess =
            Lang.get("loginPageLang.row") +
            " " +
            err.responseJSON.message +
            " " +
            Lang.get("loginPageLang.jobrepeat");
          notyf.open({
            type: "warning",
            message: mess,
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });

          setTimeout(() => (window.location.href = "new"), 1500);
        }
      },
    });
  });
});
