//only check one
$(".basic").on("change", function () {
  $(".basic").not(this).prop("checked", false);
});

$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  function quickSearch() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = $("#numbersearch").val();
    //var isISN = $("#toggle-state").is(":checked");
    console.log(input); // test
    // filter = input.value;
    // Loop through all table rows, and hide those who don't match the search query
    $(".isnRows").each(function (i, obj) {
      txtValue = $(this).find("input[id^='datae']").val();
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

  var count = $("#count").val();

  for (var i = 0; i < count; i++) {
    var status = $("#datab" + i).val();
    if (status === "已完成") {
      document.getElementById("check" + i).setAttribute("disabled", "disabled");
      //document.getElementById("list"+i).style.backgroundColor = "black";
    }
  }

  $("#bulist").on("submit", function (e) {
    e.preventDefault();

    var select = $(document.activeElement).val();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var i = $("input:checked").val();

    var list = $("#dataa" + i).val();
    checked = $("input[type=checkbox]:checked").length;

    var record = [];
    var title = [];

    for (var k = 0; k < 17; k++) {
      title[k] = $("#title" + k).val();
    }

    var titlecount = $("#titlecount").val();
    var titlename = $("#titlename").val();

    $("#bulist :checkbox").each(function () {
      //if(values.indexOf($(this).val()) === -1){
      record.push($(this).val());
      // }
    });

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
    var data13 = [];
    var data14 = [];
    var data15 = [];
    var data16 = [];

    for (let k = 0; k < record.length; k++) {
      data0.push($("#dataa" + record[k]).val());
      data1.push($("#datab" + record[k]).val());
      data2.push($("#datac" + record[k]).val());
      data3.push($("#datad" + record[k]).val());
      data4.push($("#datae" + record[k]).val());
      data5.push($("#dataf" + record[k]).val());
      data6.push($("#datag" + record[k]).val());
      data7.push($("#datah" + record[k]).val());
      data8.push($("#datai" + record[k]).val());
      data9.push($("#dataj" + record[k]).val());
      data10.push($("#datak" + record[k]).val());
      data11.push($("#datal" + record[k]).val());
      data12.push($("#datam" + record[k]).val());
      data13.push($("#datan" + record[k]).val());
      data14.push($("#datao" + record[k]).val());
      data15.push($("#datap" + record[k]).val());
      data16.push($("#dataq" + record[k]).val());
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
    data.push(data13);
    data.push(data14);
    data.push(data15);
    data.push(data16);

    if (select === "刪除" || select === "Delete" || select === "删除") {
      if (!checked) {
        notyf.open({
          type: "warning",
          message: Lang.get("bupagelang.nocheck"),
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
    if (select === "刪除" || select === "Delete" || select === "删除") {
      $.ajax({
        type: "POST",
        url: "delete",
        data: {
          list: list,
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
            Lang.get("bupagelang.delete") +
            " " +
            Lang.get("bupagelang.dblist") +
            " : " +
            data.message +
            " " +
            Lang.get("bupagelang.success");
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
        error: function (err) {
          //transaction error
          if (err.status === 420) {
            console.log(err.status);
            alert(err.responseJSON.message);
            window.location.reload();
          }
        },
      });
    } else {
      $.ajax({
        type: "POST",
        url: "downloadlist",
        data: {
          AllData: JSON.stringify(data),
          title: title,
          count: count,
          titlecount: titlecount,
          titlename: titlename,
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
