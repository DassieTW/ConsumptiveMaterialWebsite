$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  $("#inboundsearch").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var titlename = $("#titlename").val();
    //download title

    var title = [];
    if (titlename === "庫存") {
      var titlecount = 13;
    } else {
      var titlecount = 12;
    }

    //download title
    for (let i = 0; i < titlecount; i++) {
      title.push($(".vtl-thead-th").eq(i).text());
    }

    var count = $(".vtl-paging-count-dropdown").val();
    console.log(count);

    if (titlename === "庫存") {
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
      var data9 = [];
      var data10 = [];
      var data11 = [];
      var data12 = [];

      for (let i = 0; i < count; i++) {
        if ($("#client" + i).val() !== null) {
          data0.push($("#client" + i).val());
          data1.push($("#isn" + i).val());
          data2.push($("#name" + i).val());
          data3.push($("#format" + i).val());
          data4.push($("#unit" + i).val());
          data5.push($("#price" + i).val());
          data6.push($("#money" + i).val());
          data7.push($("#gradea" + i).val());
          data8.push($("#month" + i).val());
          data9.push($("#stock" + i).val());
          data10.push($("#safe" + i).val());
          data11.push($("#loc" + i).val());
          data12.push($("#days" + i).val());
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
      data.push(data9);
      data.push(data10);
      data.push(data11);
      data.push(data12);
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
      var data9 = [];
      var data10 = [];
      var data11 = [];

      for (let i = 0; i < count; i++) {
        if ($("#client" + i).val() !== null) {
          data0.push($("#client" + i).val());
          data1.push($("#isn" + i).val());
          data2.push($("#name" + i).val());
          data3.push($("#format" + i).val());
          data4.push($("#unit" + i).val());
          data5.push($("#price" + i).val());
          data6.push($("#money" + i).val());
          data7.push($("#gradea" + i).val());
          data8.push($("#month" + i).val());
          data9.push($("#stock" + i).val());
          data10.push($("#monthuse" + i).val());
          data11.push($("#monthstock" + i).val());
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
      data.push(data9);
      data.push(data10);
      data.push(data11);
    }
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
  });
});
