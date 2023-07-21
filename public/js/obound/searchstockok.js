$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  var title = [];
  var titlecol = [];
  var nogood = 1;

  $("body").loadingModal({
    text: "Loading...",
    animation: "circle",
  });

  $("#QueryFlag").on("click", function (e) {
    // console.log("clicked!"); // test
    $("body").loadingModal("hide");
    $("body").loadingModal("destroy");
  });

  $("#oboundsearch").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var titlename = $("#titlename").val();
    //download title

    if (sessionStorage.getItem("oboundstocknogood") === "true") {
      nogood = 2;
    }
    var titlecount = 7;

    titlecol.push("料號");
    titlecol.push("現有庫存");
    titlecol.push("客戶別");
    titlecol.push("庫別");
    titlecol.push("最後更新時間");
    titlecol.push("品名");
    titlecol.push("規格");

    //download title
    for (let i = 0; i < titlecount; i++) {
      title.push($(".vtl-thead-th").eq(i).text());
    }

    console.log(title);
    $.ajax({
      type: "POST",
      url: "download",
      data: {
        title: title,
        titlecount: titlecount,
        titlename: titlename,
        titlecol: titlecol,
        nogood: nogood,
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
