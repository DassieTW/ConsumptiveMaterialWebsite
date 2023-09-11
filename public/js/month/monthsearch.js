$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});
function quickSearch() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = $("#numbersearch").val();
  //var isISN = $("#toggle-state").is(":checked");
  console.log(input); // test
  // filter = input.value;
  // Loop through all table rows, and hide those who don't match the search query
  $(".isnRows").each(function (i, obj) {
    txtValue = $(this).find("input[id^='number90']").val();
    // console.log("now checking text : " + txtValue); // test
    if (txtValue.indexOf(input) > -1) {
      obj.style.display = "";
    } else {
      obj.style.display = "none";
    } // if else
  });
} // quickSearch function

$("#numbersearch").on("input", function (e) {
  e.preventDefault();
  quickSearch();
});
$("#monthsearch").on("submit", function (e) {
  e.preventDefault();

  // clean up previous input results
  $(".is-invalid").removeClass("is-invalid");
  $(".invalid-feedback").remove();
  var select = $(document.activeElement).val();

  var check = [];
  var checked = $("input[type=checkbox]:checked").length;

  var count = check.length;
  var client = [];
  var machine = [];
  var production = [];
  var number90 = [];
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
  var title = [];
  var titlename = $("#titlename").val();
  var titlecount = $("#titlecount").val();

  if (select === "下載" || select === "下载" || select === "Download") {
    console.log(1);
    count = $("#count").val();
    for (let i = 0; i < count; i++) {
      data0.push($("#client" + i).val());
      data1.push($("#machine" + i).val());
      data2.push($("#production" + i).val());
      data3.push($("#90number" + i).val());
      data4.push($("#nowmps" + i).val());
      data5.push($("#nowday" + i).val());
      data6.push($("#nextmps" + i).val());
      data7.push($("#nextday" + i).val());
      data8.push($("#writetime" + i).val());
    }
    for (let i = 0; i < titlecount; i++) {
      title.push($("#title" + [i]).val());
    }
    console.log(select);
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
        AllData: JSON.stringify(data),
        count: count,
        title: title,
        titlename: titlename,
        titlecount: titlecount,
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
    if (!checked) {
      if (select === "刪除" || select === "删除" || select === "Delete") {
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
    } else {
      $("input:checkbox[name='innumber']:checked").each(function () {
        check.push($(this).val());
      });

      var count = check.length;
      for (let i = 0; i < count; i++) {
        client.push($("#client" + check[i]).val());
        machine.push($("#machine" + check[i]).val());
        production.push($("#production" + check[i]).val());
        number90.push($("#90number" + check[i]).val());
      }
      $.ajax({
        type: "POST",
        url: "monthdelete",
        data: {
          client: client,
          machine: machine,
          production: production,
          number90: number90,
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
            Lang.get("monthlyPRpageLang.month") +
            Lang.get("monthlyPRpageLang.delete") +
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

          setTimeout(() => (window.location.href = "importmonth"), 1500);
        },
        error: function (err) {
          //transaction error
          if (err.status === 420) {
            console.log(err.status);
            var mess = err.responseJSON.message;
            alert(mess);
          }
        },
      });
    }
  }
});
