$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$("#materialsearch").on("submit", function (e) {
  e.preventDefault();

  // clean up previous input results
  $(".is-invalid").removeClass("is-invalid");
  $(".invalid-feedback").remove();

  var select = ($(document.activeElement).val());
  var time = ($("#count").val());

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
  var title = [];

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

  //download title
  for (let i = 0; i < 14; i++) {
    title.push($("#title" + i).val());
  }
  //download data
  for (let i = 0; i < time; i++) {
    data0.push($("#data0" + i).val());
    data1.push($("#data1" + i).val());
    data2.push($("#data2" + i).val());
    data3.push($("#data3" + i).val());
    data4.push($("#data4" + i).val());
    data5.push($("#data5" + i).val());
    data6.push($("#data6" + i).val());
    data7.push($("#data7" + i).val());
    data8.push($("#data8" + i).val());
    data9.push($("#data9" + i).val());
    data10.push($("#data10" + i).val());
    data11.push($("#data11" + i).val());
    data12.push($("#data12" + i).val());
    data13.push($("#data13" + i).val());
  }


  $("input:checkbox[name=innumber]:checked").each(function () {
    check.push($(this).val());
  });

  if (select == "刪除" || select == "删除" || select == "Delete") {
    select = "刪除";
  }
  if (select == "更新" || select == "Update") {
    select = "更新";
  }
  if (select == "下載" || select == "下载" || select == "Download") {
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
    if(gradea[i] === 'Yes') gradea[i] = '是';
    if(gradea[i] === 'No') gradea[i] = '否';
    if(month[i] === 'Yes') month[i] = '是';
    if(month[i] === 'No') month[i] = '否';
    if(belong[i] === 'Unit consumption' || belong[i] === '单耗') belong[i] = '單耗';
    if(belong[i] === 'Station') belong[i] = '站位';
  }

  checked = ("input[type=checkbox]:checked").length;


  if (!checked && select != "下載") {
    alert(Lang.get("basicInfoLang.nocheck"));
    return false;
  }

  console.log(count);
  console.log(check);

  console.log(number);
  console.log(belong);

  if (select != "下載") {
    $.ajax({
      type: "POST",
      url: "materialchangeordel",
      data: {
        number: number,
        gradea: gradea,
        month: month,
        send: send,
        belong: belong,
        price: price,
        money: money,
        unit: unit,
        mpq: mpq,
        moq: moq,
        lt: lt,
        safe: safe,
        select: select,
        count: count,
        check: check,
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
      success: function (data) {
        console.log(data.boolean);

        var mess =
          Lang.get("basicInfoLang.change") +
          " / " +
          Lang.get("basicInfoLang.delete") +
          " " +
          Lang.get("basicInfoLang.success");
        alert(mess);
        //window.location.href = "material";
        window.location.reload();
      },
      error: function (err) {
        //非月請購沒填安全庫存
        if (err.status == 420) {
          var mess = err.responseJSON.row +' '+ err.responseJSON.message;
          alert(mess);

          console.log(err.responseJSON.message);
          console.log(err.status);
          //transaction error
        } else if (err.status == 409) {
            console.log(err.status);
        }
      },
    });
  }
  else{
    $.ajax({
        type: 'POST',
        url: "materialchangeordel",
        data: {
            title: title, data0: data0, data1: data1, data2: data2, data3: data3,
            data4: data4, data5: data5, data6: data6, data7: data7, data8: data8, data9: data9,
            data10: data10,data11: data11,data12: data12,data13: data13,select :select,time:time
        },
        xhrFields: {
            responseType: 'blob', // to avoid binary data being mangled on charset conversion
        },

        beforeSend: function () {
            // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
            $('body').loadingModal({
                text: 'Loading...',
                animation: 'circle'
            });
        },
        complete: function () {
            $('body').loadingModal('hide');
        },

        success: function (blob, status, xhr) {

            console.log(status); // test
            // check for a filename

            var filename = "";
            var disposition = xhr.getResponseHeader('Content-Disposition');
            if (disposition && disposition.indexOf('attachment') !== -1) {
                var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                var matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
            }

            if (typeof window.navigator.msSaveBlob !== 'undefined') {
                // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                window.navigator.msSaveBlob(blob, filename);
            } else {
                var URL = window.URL || window.webkitURL;
                var downloadUrl = URL.createObjectURL(blob);

                if (filename) {
                    // use HTML5 a[download] attribute to specify filename
                    var a = document.createElement("a");
                    // safari doesn't support this yet
                    if (typeof a.download === 'undefined') {
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

                setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {

            console.warn(jqXHR.responseText);
            alert(errorThrown);
        }
    });
  }
});
