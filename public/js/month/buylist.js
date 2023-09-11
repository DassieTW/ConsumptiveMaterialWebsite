$(function () {
  var counta = $("#counta").val();
  var countb = $("#countb").val();

  for (var i = 0; i < counta; i++) {
    var buyamounta = $("#buyamounta" + i).val();
    var pricea = $("#pricea" + i).val();
    var ratea = $("#ratea" + i).val();
    var buymoneya = parseFloat(buyamounta * pricea * ratea).toFixed(5);
    $("#buymoneya" + i).val(buymoneya);
  }
  for (var i = 0; i < countb; i++) {
    var buyamountb = $("#buyamountb" + i).val();
    var priceb = $("#priceb" + i).val();
    var rateb = $("#rateb" + i).val();
    var buymoneyb = parseFloat(buyamountb * priceb * rateb).toFixed(5);
    $("#buymoneyb" + i).val(buymoneyb);
  }
  $("input").on("change", function () {
    for (var i = 0; i < counta; i++) {
      var buyamounta = $("#buyamounta" + i).val();
      var pricea = $("#pricea" + i).val();
      var ratea = $("#ratea" + i).val();
      var buymoneya = parseFloat(buyamounta * pricea * ratea).toFixed(5);
      $("#buymoneya" + i).val(buymoneya);
    }
    for (var i = 0; i < countb; i++) {
      var buyamountb = $("#buyamountb" + i).val();
      var priceb = $("#priceb" + i).val();
      var rateb = $("#rateb" + i).val();
      var buymoneyb = parseFloat(buyamountb * priceb * rateb).toFixed(5);
      $("#buymoneyb" + i).val(buymoneyb);
    }
  });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $("#writesrm").on("click", function () {
    var checka = [];
    $("input:checkbox[name=innumbera]:checked").each(function () {
      checka.push($(this).val());
    });
    var checkb = [];
    $("input:checkbox[name=innumberb]:checked").each(function () {
      checkb.push($(this).val());
    });

    var checkcounta = checka.length;
    var checkcountb = checkb.length;

    var writesrm = prompt(Lang.get("monthlyPRpageLang.writesrm"));
    for (let i = 0; i < checkcounta; i++) {
      $("#srmnumbera" + checka[i]).val(writesrm);
    }
    for (let i = 0; i < checkcountb; i++) {
      $("#srmnumberb" + checkb[i]).val(writesrm);
    }
    $(".innumber").prop("checked", false);
  });

  $("#buylist").on("submit", function (e) {
    e.preventDefault();
    var select = $(document.activeElement).val();
    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();
    var titlename = $("#titlename").val();
    counta = counta || 0;
    countb = countb || 0;
    counta = parseInt(counta);
    countb = parseInt(countb);

    var count = counta + countb;
    var check1 = 0;
    var data = [];
    var srm = [];
    var client = [];
    var number = [];
    var name = [];
    var moq = [];
    var nextneed = [];
    var nowneed = [];
    var price = [];
    var money = [];
    var rate = [];
    var amount = [];
    var stock = [];
    var buyamount = [];
    var buymoney = [];
    var check = [];
    var format = [];

    for (let i = 0; i < counta; i++) {
      srm.push($("#srmnumbera" + i).val());
      client.push($("#clienta" + i).val());
      number.push($("#numbera" + i).val());
      name.push($("#namea" + i).val());
      format.push($("#formata" + i).val());
      price.push($("#pricea" + i).val());
      money.push($("#moneya" + i).val());
      nowneed.push($("#nowneeda" + i).val());
      nextneed.push($("#nextneeda" + i).val());
      stock.push($("#stocka" + i).val());
      amount.push($("#amounta" + i).val());
      buyamount.push($("#buyamounta" + i).val());
      buymoney.push($("#buymoneya" + i).val());
      rate.push($("#ratea" + i).val());
      moq.push($("#moqa" + i).val());
    }

    for (let i = 0; i < countb; i++) {
      srm.push($("#srmnumberb" + i).val());
      client.push($("#clientb" + i).val());
      number.push($("#numberb" + i).val());
      name.push($("#nameb" + i).val());
      format.push($("#formatb" + i).val());
      price.push($("#priceb" + i).val());
      money.push($("#moneyb" + i).val());
      nowneed.push($("#nowneedb" + i).val());
      nextneed.push($("#nextneedb" + i).val());
      stock.push($("#stockb" + i).val());
      amount.push($("#amountb" + i).val());
      buyamount.push($("#buyamountb" + i).val());
      buymoney.push($("#buymoneyb" + i).val());
      rate.push($("#rateb" + i).val());
      moq.push($("#moqb" + i).val());
    }

    if (select === "提交" || select === "Submit") {
      for (let i = 0; i < counta + countb; i++) {
        if (parseInt(buyamount[i]) > 0 && srm[i] === "") {
          if (i < counta) {
            $("#srmnumbera" + i).addClass("is-invalid");
          } else {
            let j = i - counta;
            $("#srmnumberb" + j).addClass("is-invalid");
          }
          i++;
          var mess =
            Lang.get("monthlyPRpageLang.row") +
            " " +
            i +
            " " +
            Lang.get("monthlyPRpageLang.nowrite") +
            Lang.get("monthlyPRpageLang.srm");
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
        } else if (parseInt(buyamount[i]) === 0) {
          check.push(0);
        } else {
          check.push(1);
          continue;
        }
      }
    } // if clicked submit

    data.push(srm);
    data.push(client);
    data.push(number);
    data.push(name);
    data.push(format);
    data.push(price);
    data.push(money);
    data.push(nowneed);
    data.push(nextneed);
    data.push(stock);
    data.push(amount);
    data.push(buyamount);
    data.push(buymoney);
    data.push(rate);
    data.push(moq);

    if (select === "提交" || select === "Submit") {
      for (let i = 0; i < counta + countb; i++) {
        if (check[i] === 0) {
          check1++;
        }
      }

      if (check1 === count) {
        notyf.open({
          type: "warning",
          message: Lang.get("monthlyPRpageLang.nodata"),
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
        url: "buylistsubmit",
        data: {
          AllData: JSON.stringify(data),
          count: count,
          check: check,
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
            Lang.get("monthlyPRpageLang.PR") +
            Lang.get("monthlyPRpageLang.submit") +
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

          setTimeout(() => (window.location.href = "buylistmake"), 1500);
        },
        error: function (err) {
          //transaction error
          if (err.status === 420) {
            var mess = err.responseJSON.message;
            alert(mess);
            return false;
          }
        },
      });
    } else {
      $.ajax({
        type: "POST",
        url: "buylistdownload",
        data: {
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
