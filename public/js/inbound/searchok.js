$(".vtl-thead-checkbox").attr("disabled", true);

$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  var title = [];
  var check;
  var titlecount = 9;
  var titlename = "入庫查詢";
  //download title
  for (let i = 0; i < 9; i++) {
    title.push(
      $(".vtl-thead-th")
        .eq(i + 1)
        .text()
    );
  }

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

    if (select == "刪除" || select == "Delete" || select == "删除") {
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
    var amount = $("#inboundnum" + check).val();
    var position = $("#position" + check).val();
    var inpeople = $("#inboundpeople" + check).val();
    var client = $("#client" + check).val();
    var inreason = $("#inboundreason" + check).val();

    if (select === "刪除" || select === "Delete" || select === "删除") {
      $.ajax({
        type: "POST",
        url: "delete",
        data: {
          list: list,
          number: number,
          amount: amount,
          position: position,
          inpeople: inpeople,
          client: client,
          inreason: inreason,
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
            Lang.get("inboundpageLang.delete") +
            Lang.get("inboundpageLang.success") +
            Lang.get("inboundpageLang.inlist") +
            " : " +
            data.list +
            "\n" +
            Lang.get("inboundpageLang.client") +
            " : " +
            data.client +
            " " +
            Lang.get("inboundpageLang.isn") +
            " : " +
            data.number;
          alert(mess);
          window.location.href = "search";
        },
        error: function (err) {
          //庫存小於入庫數量
          if (err.status == 420) {
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
          else if (err.status == 421) {
            console.log(err.status);
            var mess = err.responseJSON.message;
            alert(mess);
          }
        },
      });
    } else {
      var count = $(".vtl-paging-count-dropdown").val();
      console.log(count);
      var data = [];
      var data0 = [];
      var data1 = [];
      var data2 = [];
      var data3 = [];
      var data4 = [];
      var data5 = [];
      var data6 = [];
      var data7 = [];
      var data8 = [];
      for (let i = 0; i < count; i++) {
        if ($("#inboundlist" + i).val() !== null) {
          data0.push($("#inboundlist" + i).val());
          data1.push($("#number" + i).val());
          data2.push($("#inboundnum" + i).val());
          data3.push($("#position" + i).val());
          data4.push($("#inboundpeople" + i).val());
          data5.push($("#client" + i).val());
          data6.push($("#inboundreason" + i).val());
          data7.push($("#inboundtime" + i).val());
          data8.push($("#remark" + i).val());
        }
      }
      data.push(data0);
      data.push(data1);
      data.push(data2);
      data.push(data3);
      data.push(data4);
      data.push(data5);
      data.push(data6);
      data.push(data7);
      data.push(data8);
      $.ajax({
        type: "POST",
        url: "download",
        data: {
          title: title,
          titlecount: titlecount,
          titlename: titlename,
          AllData: JSON.stringify(data),
          count: count,
        },
        xhrFields: {
          responseType: "blob", // to avoid binary data being mangled on charset conversion
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

        success: function (blob, status, xhr) {
          console.log(status); // test
          // check for a filename

          var filename = "";
          var disposition = xhr.getResponseHeader("Content-Disposition");
          if (disposition && disposition.indexOf("attachment") !== -1) {
            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            var matches = filenameRegex.exec(disposition);
            if (matches != null && matches[1])
              filename = matches[1].replace(/['"]/g, "");
          }

          if (typeof window.navigator.msSaveBlob !== "undefined") {
            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
            window.navigator.msSaveBlob(blob, filename);
          } else {
            var URL = window.URL || window.webkitURL;
            var downloadUrl = URL.createObjectURL(blob);

            if (filename) {
              // use HTML5 a[download] attribute to specify filename
              var a = document.createElement("a");
              // safari doesn't support this yet
              if (typeof a.download === "undefined") {
                window.location.href = downloadUrl;
              } else {
                a.href = downloadUrl;
                a.download = decodeURIComponent(filename);
                console.log(decodeURIComponent(filename));
                document.body.appendChild(a);
                a.click();
              }
            } else {
              window.location.href = downloadUrl;
            }

            setTimeout(function () {
              URL.revokeObjectURL(downloadUrl);
            }, 100); // cleanup
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.warn(jqXHR.responseText);
          alert(errorThrown);
        },
      });
    }
  });
});
