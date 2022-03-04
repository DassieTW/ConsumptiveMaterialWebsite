$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {


    function quickSearch() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = $("#numbersearch").val();
        //var isISN = $("#toggle-state").is(":checked");
        console.log(input); // test
        // filter = input.value;
        // Loop through all table rows, and hide those who don't match the search query
        $('.isnRows').each(function (i, obj) {
            txtValue = $(this).find("input[id^='number']").val();
            // console.log("now checking text : " + txtValue); // test
            if (txtValue.indexOf(input) > -1) {
                obj.style.display = "";

            } else {
                obj.style.display = "none";
            } // if else
        });
    } // quickSearch function


    $("#numbersearch").on('input', function (e) {
        e.preventDefault();
        quickSearch();
    });

    var downloadcount = ($("#count").val());
    var data = [];
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

    $("#materialsearch").on("submit", function (e) {
        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").remove();

        //download data
        for (let i = 0; i < downloadcount; i++) {
            data0.push($("#number" + i).val());
            data1.push($("#name" + i).val());
            data2.push($("#format" + i).val());
            data3.push($("#gradea" + i).val());
            data4.push($("#month" + i).val());
            data5.push($("#send" + i).val());
            data6.push($("#belong" + i).val());
            data7.push($("#price" + i).val());
            data8.push($("#money" + i).val());
            data9.push($("#unit" + i).val());
            data10.push($("#mpq" + i).val());
            data11.push($("#moq" + i).val());
            data12.push($("#lt" + i).val());
            data13.push($("#safe" + i).val());
        }

        var select = ($(document.activeElement).val());
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

        $("input:checkbox[name=innumber]:checked").each(function () {
            check.push($(this).val());
        });

        if (select == "刪除" || select == "删除" || select == "Delete") {
            select = "刪除";
        }
        if (select == "更新" || select == "Update") {
            select = "更新";
        }
        if (select == "下載" || select == "下载" || select == "Download Excel") {
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
            if (month[i] == '否' && safe[i] == '') {
                row = parseInt(check[i]) + 1;
                var mess = Lang.get("basicInfoLang.row") + ' : ' + row + ' ' + Lang.get("basicInfoLang.safeerror");
                alert(mess);
                $("#safe" + check[i]).addClass("is-invalid");
                return false;
            }
        }

        checked = ("input[type=checkbox]:checked").length;

        if (count == 0 && select != "下載") {
            notyf.open({
                type: 'warning',
                message: Lang.get('inboundpageLang.nocheck'),
                duration: 3000, //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
            return false;
        }

        if (select != "下載") {
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
        } else {
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
        }

        if (select != "下載") {
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
                success: function (data) {


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
                    //transaction error
                    if (err.status == 409) {
                        console.log(err.status);
                    }
                },
            });
        } else {

            $.ajax({
                type: 'POST',
                url: "materialchangeordel",
                data: {
                    title: title,
                    AllData: JSON.stringify(data),
                    select: select,
                    downloadcount: downloadcount
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
                    $('body').loadingModal('destroy');
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

                        setTimeout(function () {
                            URL.revokeObjectURL(downloadUrl);
                        }, 100); // cleanup
                    }


                },
                error: function (jqXHR, textStatus, errorThrown) {

                    console.warn(jqXHR.responseText);
                    alert(errorThrown);
                }
            });
        }
    });
});
