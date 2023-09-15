$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("body").loadingModal({
    text: "Loading...",
    animation: "circle",
  });

  $("#QueryFlag").on("click", function (e) {
    // console.log("clicked!"); // test
    $("body").loadingModal("hide");
    $("body").loadingModal("destroy");
  });

  $("#notmonthform").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var title = [];
    var titlename = $("#titlename").val();
    var titlecount = 5;
    var titlecol = [];
    var select = $(document.activeElement).val();
    var check = [];
    var number = [];

    if (select === "下載" || select === "Download" || select === "下载") {
      //download title
      for (let i = 1; i < 6; i++) {
        title.push($(".vtl-thead-th").eq(i).text());
      }

      titlecol.push("料號");
      titlecol.push("品名");
      titlecol.push("請購數量");
      titlecol.push("上傳時間");
      titlecol.push("說明");

      console.log(titlename);
      console.log(title);
      $.ajax({
        type: "POST",
        url: "download",
        data: {
          title: title,
          titlename: titlename,
          titlecount: titlecount,
          titlecol: titlecol,
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
    } else {
      $("input:checkbox:checked").each(function () {
        check.push($(this).val());
      });
      var count = check.length;

      for (let i = 0; i < count; i++) {
        number.push($("#number" + check[i]).val());
      }

      console.log(number);
      if (number.length === 0) {
        notyf.open({
          type: "warning",
          message: Lang.get("monthlyPRpageLang.nocheck"),
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
      $.ajax({
        type: "POST",
        url: "notmonthdelete",
        data: {
          number: number,
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
            " " +
            data.message +
            " " +
            Lang.get("monthlyPRpageLang.record") +
            " " +
            Lang.get("monthlyPRpageLang.notmonth") +
            " " +
            Lang.get("monthlyPRpageLang.delete") +
            " " +
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

          setTimeout(() => window.location.reload(), 1500);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          console.warn(jqXHR.responseText);
          alert(errorThrown);
        },
      });
    }
  });
});
