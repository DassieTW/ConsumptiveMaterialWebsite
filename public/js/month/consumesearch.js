$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $("#email").on("change", function (event) {
    $("#emailTail").text($(this).val());
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
      txtValue = $(this).find("input[id^='number']").val();
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
  $("#consume").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var select = $(document.activeElement).val();
    var count = $("#count").val();
    var client = [];
    var number = [];
    var number90 = [];
    var production = [];
    var machine = [];
    var amount = [];
    var check = [];
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
    var title = [];
    var titlename = $("#titlename").val();
    var titlecount = $("#titlecount").val();

    for (let i = 0; i < count; i++) {
      data0.push($("#client" + i).val());
      data1.push($("#machine" + i).val());
      data2.push($("#production" + i).val());
      data3.push($("#number" + i).val());
      data4.push($("#name" + i).val());
      data5.push($("#format" + i).val());
      data6.push($("#amount" + i).val());
      data7.push($("#number90" + i).val());
      data8.push($("#email" + i).val());
      data9.push($("#status" + i).val());
    }
    for (let i = 0; i < titlecount; i++) {
      title.push($("#title" + [i]).val());
    }

    $("input:checkbox[name=innumber]:checked").each(function () {
      check.push($(this).val());
    });

    var count = check.length;

    for (let i = 0; i < count; i++) {
      client.push($("#client" + check[i]).val());
      number.push($("#number" + check[i]).val());
      number90.push($("#number90" + check[i]).val());
      production.push($("#production" + check[i]).val());
      machine.push($("#machine" + check[i]).val());
      amount.push($("#amount" + check[i]).val());
    }
    let checked = $("input[type=checkbox]:checked").length;
    if (
      select === "刪除" ||
      select === "删除" ||
      select === "Delete" ||
      select === "更新" ||
      select === "Update"
    ) {
      if (!checked) {
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
    }

    if (select === "刪除" || select === "删除" || select === "Delete") {
      select = "刪除";
    }
    if (select === "下載" || select === "下载" || select === "Download") {
      select = "下載";
      var count = $("#count").val();
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
      console.log(titlecount);

      $.ajax({
        type: "POST",
        url: "consumedownload",
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
      if (select === "更新" || select === "Update") {
        if ($("#email").val() === null) {
          notyf.open({
            type: "warning",
            message: Lang.get("monthlyPRpageLang.noemail"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });
          document.getElementById("email").classList.add("is-invalid");
          document.getElementById("email").classList.add("is-invalid");
          document.getElementById("email").focus();
          return false;
        } else {
          var email = $("#email option:selected").text();
          console.log(email);
        }

        select = "更新";
      }

      $.ajax({
        type: "POST",
        url: "consumechangeordelete",
        data: {
          client: client,
          number: number,
          number90: number90,
          production: production,
          machine: machine,
          amount: amount,
          select: select,
          email: email,
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
          console.log(data);
          if (data.status === 201) {
            var mess =
              Lang.get("monthlyPRpageLang.total") +
              " " +
              data.message +
              " " +
              Lang.get("monthlyPRpageLang.record") +
              " " +
              Lang.get("monthlyPRpageLang.isn") +
              " " +
              Lang.get("monthlyPRpageLang.consume") +
              " " +
              Lang.get("monthlyPRpageLang.change") +
              " " +
              Lang.get("monthlyPRpageLang.submit") +
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

            setTimeout(() => (window.location.href = "consume"), 1500);
          } else {
            var mess =
              Lang.get("monthlyPRpageLang.total") +
              " " +
              data.message +
              " " +
              Lang.get("monthlyPRpageLang.record") +
              " " +
              Lang.get("monthlyPRpageLang.isn") +
              " " +
              Lang.get("monthlyPRpageLang.consume") +
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

            setTimeout(() => (window.location.href = "consume"), 1500);
          }
        },
        error: function (err) {
          console.log(err);

          var mess = err.responseJSON.message;
          alert(mess);
        },
      });
    }
  });
});
