$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});
$(document).ready(function () {
  var title = [];
  var titlecol = [];

  $("#picktable").on("submit", function (e) {
    e.preventDefault();

    var titlecount = 20;
    var titlename = $("#titlename").val();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    for (let i = 0; i < 20; i++) {
      title.push($(".vtl-thead-th").eq(i).text());
    }

    titlecol.push("客戶別");
    titlecol.push("機種");
    titlecol.push("製程");
    titlecol.push("領用原因");
    titlecol.push("線別");
    titlecol.push("料號");
    titlecol.push("品名");
    titlecol.push("規格");
    titlecol.push("預領數量");
    titlecol.push("實際領用數量");
    titlecol.push("備註");
    titlecol.push("實領差異原因");
    titlecol.push("庫別");
    titlecol.push("領料人員");
    titlecol.push("領料人員工號");
    titlecol.push("發料人員");
    titlecol.push("發料人員工號");
    titlecol.push("領料單號");
    titlecol.push("開單時間");
    titlecol.push("出庫時間");

    $.ajax({
      type: "POST",
      url: "download",
      data: {
        title: title,
        titlecount: titlecount,
        titlename: titlename,
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
  });

  $("#backtable").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var titlecount = 21;
    var titlename = $("#titlename").val();
    //download title
    for (let i = 0; i < 21; i++) {
      title.push($(".vtl-thead-th").eq(i).text());
    }

    titlecol.push("客戶別");
    titlecol.push("機種");
    titlecol.push("製程");
    titlecol.push("退回原因");
    titlecol.push("線別");
    titlecol.push("料號");
    titlecol.push("品名");
    titlecol.push("規格");
    titlecol.push("預退數量");
    titlecol.push("實際退回數量");
    titlecol.push("備註");
    titlecol.push("實退差異原因");
    titlecol.push("庫別");
    titlecol.push("收料人員");
    titlecol.push("收料人員工號");
    titlecol.push("退料人員");
    titlecol.push("退料人員工號");
    titlecol.push("退料單號");
    titlecol.push("開單時間");
    titlecol.push("入庫時間");
    titlecol.push("功能狀況");

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    $.ajax({
      type: "POST",
      url: "download",
      data: {
        title: title,
        titlecount: titlecount,
        titlename: titlename,
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
  });
});
