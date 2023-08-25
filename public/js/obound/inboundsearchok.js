$(".vtl-thead-checkbox").attr("disabled", true);

$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  var check;

  $("body").loadingModal({
    text: "Loading...",
    animation: "circle",
  });

  $("#QueryFlag").on("click", function (e) {
    // console.log("clicked!"); // test
    $("body").loadingModal("hide");
    $("body").loadingModal("destroy");
  });

  $("#inboundsearch").on("submit", function (e) {
    e.preventDefault();

    $("input:checkbox:checked").each(function () {
      check = $(this).val();
    });
    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var checked;
    checked = $("input[type=checkbox]:checked").length;
    var select = $(document.activeElement).val();

    if (select === "刪除" || select === "Delete" || select === "删除") {
      if (!checked) {
        notyf.open({
          type: "warning",
          message: Lang.get("inboundpageLang.nocheck"),
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
    }

    console.log(check);
    var list = $("#inboundlist" + check).val();
    var number = $("#number" + check).val();
    var name = $("#name" + check).val();
    var format = $("#format" + check).val();
    var client = $("#client" + check).val();
    var bound = $("#bound" + check).val();
    var amount = $("#inboundnum" + check).val();
    var inpeople = $("#inboundpeople" + check).val();
    var inreason = $("#inboundreason" + check).val();
    var time = $("#inboundtime" + check).val();

    $.ajax({
      type: "POST",
      url: "delete",
      data: {
        list: list,
        number: number,
        amount: amount,
        bound: bound,
        inpeople: inpeople,
        client: client,
        inreason: inreason,
        time: time,
        name: name,
        format: format,
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
          Lang.get("oboundpageLang.delete") +
          Lang.get("oboundpageLang.success") +
          Lang.get("oboundpageLang.inlist") +
          " : " +
          data.list +
          "\n" +
          Lang.get("oboundpageLang.client") +
          " : " +
          data.client +
          " " +
          Lang.get("oboundpageLang.isn") +
          " : " +
          data.number;
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

        setTimeout(() => (window.location.href = "inboundsearch"), 1500);
      },
      error: function (err) {
        //庫存小於入庫數量
        if (err.status === 420) {
          var mess =
            Lang.get("inboundpageLang.lessstock") +
            "\n" +
            Lang.get("inboundpageLang.nowstock") +
            " : " +
            err.responseJSON.stock +
            " " +
            Lang.get("inboundpageLang.inboundnum") +
            " : " +
            err.responseJSON.amount;
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
          return false;
        }
        //transaction error
        else if (err.status === 421) {
          console.log(err.status);
          var mess = err.responseJSON.message;
          alert(mess);
        }
      },
    });
  });
});
