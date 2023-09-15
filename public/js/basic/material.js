$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  var data = [];
  var title = [];
  //download title
  for (let i = 0; i < 14; i++) {
    title.push(
      $(".vtl-thead-th")
        .eq(i + 1)
        .text()
    );
  } // for

  $("body").loadingModal({
    text: "Loading...",
    animation: "circle",
  });

  $("#QueryFlag").on("click", function (e) {
    // console.log("clicked!"); // test
    $("body").loadingModal("hide");
    $("body").loadingModal("destroy");
  });

  $("#materialsearch").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var select = $(document.activeElement).val();
    var row = 0;
    var check = [];
    var number = [];
    var gradea = [];
    var month = [];
    var send = [];
    var belong = [];
    var price = [];
    var money = [];
    var unit = [];
    var mpq = [];
    var moq = [];
    var lt = [];
    var safe = [];

    $("input:checkbox:checked").each(function () {
      check.push($(this).val());
    });

    if (select === "刪除" || select === "删除" || select === "Delete") {
      select = "刪除";
    }
    if (select === "更新" || select === "Update") {
      select = "更新";
    }
    if (select === "下載" || select === "下载" || select === "Download Excel") {
      select = "下載";
    }

    var count = check.length;

    for (let i = 0; i < count; i++) {
      number.push($("#number" + check[i]).val());
      gradea.push($("#gradea" + check[i]).val());
      month.push($("#month" + check[i]).val());
      send.push($("#send" + check[i]).val());
      belong.push($("#belong" + check[i]).val());
      price.push($("#price" + check[i]).val());
      money.push($("#money" + check[i]).val());
      unit.push($("#unit" + check[i]).val());
      mpq.push($("#mpq" + check[i]).val());
      moq.push($("#moq" + check[i]).val());
      lt.push($("#lt" + check[i]).val());
      safe.push($("#safe" + check[i]).val());
    }
    for (let i = 0; i < count; i++) {
      if (month[i] === "否" && safe[i] === "") {
        row = parseInt(check[i]) + 1;
        var mess =
          Lang.get("basicInfoLang.row") +
          " : " +
          row +
          " " +
          Lang.get("basicInfoLang.safeerror");
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
        $("#safe" + check[i]).addClass("is-invalid");
        return false;
      }
    }

    if (count === 0 && select !== "下載") {
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

    if (select !== "下載") {
      data.push(number);
      data.push(gradea);
      data.push(month);
      data.push(send);
      data.push(belong);
      data.push(price);
      data.push(money);
      data.push(unit);
      data.push(mpq);
      data.push(moq);
      data.push(lt);
      data.push(safe);
    }
    if (select !== "下載") {
      $.ajax({
        type: "POST",
        url: "materialchangeordel",
        data: {
          AllData: JSON.stringify(data),
          count: count,
          check: check,
          select: select,
        },
        dataType: "json", // expected respose datatype from server
        //async: false,

        beforeSend: function () {
          // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
          $("body").loadingModal({
            text: "Loading...",
            animation: "circle",
          });
        },
        complete: function () {
          $("body").loadingModal("hide");
        },
        success: function () {
          var mess =
            Lang.get("basicInfoLang.change") +
            " / " +
            Lang.get("basicInfoLang.delete") +
            " " +
            Lang.get("basicInfoLang.success");
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
          //window.location.href = "material";
          setTimeout(() => window.location.reload(), 1500); // 3
        },
        error: function (err) {
          //transaction error
          if (err.status === 409) {
            console.log(err.status);
          }
        },
      });
    } else {
      $.ajax({
        type: "POST",
        url: "materialchangeordel",
        data: {
          title: title,
          select: select,
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
