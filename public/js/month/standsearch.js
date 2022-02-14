var count = $("#count").val();
for (var i = 0; i < count; i++) {
    var nowpeople = $("#datai" + i).val();
    var nowline = $("#dataj" + i).val();
    var nowclass = $("#datak" + i).val();
    var nowuse = $("#datal" + i).val();
    var nowchange = $("#datam" + i).val();
    var nextpeople = $("#datao" + i).val();
    var nextline = $("#datap" + i).val();
    var nextclass = $("#dataq" + i).val();
    var nextuse = $("#datar" + i).val();
    var nextchange = $("#datas" + i).val();
    var mpq = $("#datad" + i).val();
    var lt = $("#datae" + i).val();
    var now = (nowpeople * nowclass * nowline * nowuse * nowchange) / mpq;
    var next = (nextpeople * nextclass * nextline * nextuse * nextchange) / mpq;
    var safe = next * lt;
    now = now.toFixed(2);
    next = next.toFixed(2);
    safe = safe.toFixed(2);
    $('#datan' + i).val(now);
    $('#datat' + i).val(next);
    $('#datau' + i).val(safe);
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $("input").change(function () {

        for (var i = 0; i < count; i++) {
            var nowpeople = $("#datai" + i).val();
            var nowline = $("#dataj" + i).val();
            var nowclass = $("#datak" + i).val();
            var nowuse = $("#datal" + i).val();
            var nowchange = $("#datam" + i).val();
            var nextpeople = $("#datao" + i).val();
            var nextline = $("#datap" + i).val();
            var nextclass = $("#dataq" + i).val();
            var nextuse = $("#datar" + i).val();
            var nextchange = $("#datas" + i).val();
            var mpq = $("#datad" + i).val();
            var lt = $("#datae" + i).val();
            var now = (nowpeople * nowclass * nowline * nowuse * nowchange) / mpq;
            var next = (nextpeople * nextclass * nextline * nextuse * nextchange) / mpq;
            var safe = next * lt;
            now = now.toFixed(2);
            next = next.toFixed(2);
            safe = safe.toFixed(2);
            $('#datan' + i).val(now);
            $('#datat' + i).val(next);
            $('#datau' + i).val(safe);
        }

    });


    $('#stand').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var select = ($(document.activeElement).val());

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
        var data17 = [];
        var data18 = [];
        var data19 = [];
        var data20 = [];
        var data21 = [];
        var check = [];
        var title = [];
        var titlename = $("#titlename").val();
        var jobnumber = $("#jobnumber").val();
        var email = $("#email").val();
        var titlecount = $("#titlecount").val();
        $("input:checkbox[name=innumber]:checked").each(function () {
            check.push($(this).val());
        });

        if (select == "刪除" || select == "删除" || select == "Delete") {
            select = "刪除";
            var count = check.length;
        }
        if (select == "更新" || select == "Update") {
            select = "更新";
            var count = check.length;
        }
        if (select == "下載" || select == "下载" || select == "Download") {
            select = "下載";
            var count = $("#count").val();
        }
        if (select == "刪除" || select == "更新") {
            for (let i = 0; i < count; i++) {
                data0.push($("#dataa" + i).val());
                data1.push($("#datab" + check[i]).val());
                data2.push($("#datac" + check[i]).val());
                data3.push($("#datad" + check[i]).val());
                data4.push($("#datae" + check[i]).val());
                data5.push($("#dataf" + check[i]).val());
                data6.push($("#datag" + check[i]).val());
                data7.push($("#datah" + check[i]).val());
                data8.push($("#datai" + check[i]).val());
                data9.push($("#dataj" + check[i]).val());
                data10.push($("#datak" + check[i]).val());
                data11.push($("#datal" + check[i]).val());
                data12.push($("#datam" + check[i]).val());
                data13.push($("#datan" + check[i]).val());
                data14.push($("#datao" + check[i]).val());
                data15.push($("#datap" + check[i]).val());
                data16.push($("#dataq" + check[i]).val());
                data17.push($("#datar" + check[i]).val());
                data18.push($("#datas" + check[i]).val());
                data19.push($("#datat" + check[i]).val());
                data20.push($("#datau" + check[i]).val());
                data21.push($("#datav" + check[i]).val());
            }
        } else {
            for (let i = 0; i < count; i++) {
                data0.push($("#dataa" + i).val());
                data1.push($("#datab" + i).val());
                data2.push($("#datac" + i).val());
                data3.push($("#datad" + i).val());
                data4.push($("#datae" + i).val());
                data5.push($("#dataf" + i).val());
                data6.push($("#datag" + i).val());
                data7.push($("#datah" + i).val());
                data8.push($("#datai" + i).val());
                data9.push($("#dataj" + i).val());
                data10.push($("#datak" + i).val());
                data11.push($("#datal" + i).val());
                data12.push($("#datam" + i).val());
                data13.push($("#datan" + i).val());
                data14.push($("#datao" + i).val());
                data15.push($("#datap" + i).val());
                data16.push($("#dataq" + i).val());
                data17.push($("#datar" + i).val());
                data18.push($("#datas" + i).val());
                data19.push($("#datat" + i).val());
                data20.push($("#datau" + i).val());
                data21.push($("#datav" + i).val());
            }
        }
        for (let i = 0; i < titlecount; i++) {
            title.push($("#title" + [i]).val());
        }

        checked = $("input[type=checkbox]:checked").length;


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
        data.push(data17);
        data.push(data18);
        data.push(data19);
        data.push(data20);
        data.push(data21);

        if (select == "刪除" || select == "删除" || select == "Delete") {
            select = "刪除";
        }
        if (select == "更新" || select == "Update") {
            if (!jobnumber) {
                alert(Lang.get('monthlyPRpageLang.nopeople'));
                document.getElementById('jobnumber').classList.add("is-invalid");
                return false;
            } else if (!email) {
                alert(Lang.get('monthlyPRpageLang.noemail'));
                document.getElementById('email').classList.add("is-invalid");
                return false;
            }
            select = "更新";
        }
        if (select == "刪除" || select == "更新") {
            if (!checked) {
                alert(Lang.get('monthlyPRpageLang.nocheck'));
                return false;
            }
            $.ajax({
                type: 'POST',
                url: "standchangeordelete",
                data: {
                    AllData: JSON.stringify(data),
                    select: select,
                    count: count,
                    title: title,
                    titlename: titlename,
                    jobnumber: jobnumber,
                    email: email
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
                success: function (data) {

                    console.log(data);
                    if (data.status == 201) {
                        var mess = Lang.get('monthlyPRpageLang.total') + ' ' + (data.message) + ' ' + Lang.get('monthlyPRpageLang.record') + ' ' +
                            Lang.get('monthlyPRpageLang.stand') + ' ' +
                            Lang.get('monthlyPRpageLang.change') + ' ' + Lang.get('monthlyPRpageLang.submit') + ' ' + Lang.get('monthlyPRpageLang.success');
                        alert(mess);
                        window.location.href = "stand";

                    } else {
                        var mess = Lang.get('monthlyPRpageLang.total') + ' ' + (data.message) + ' ' + Lang.get('monthlyPRpageLang.record') + ' ' +
                            Lang.get('monthlyPRpageLang.stand') + ' ' +
                            Lang.get('monthlyPRpageLang.delete') + ' ' + Lang.get('monthlyPRpageLang.success');
                        alert(mess);
                        window.location.href = "stand";
                    }
                },
                error: function (err) {
                    console.log(err);

                    var mess = err.responseJSON.message;
                    alert(mess);

                },
            });

        } else {
            $.ajax({
                type: 'POST',
                url: "download",
                data: {
                    AllData: JSON.stringify(data),
                    count: count,
                    title: title,
                    titlename: titlename,
                    titlecount: titlecount,
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
