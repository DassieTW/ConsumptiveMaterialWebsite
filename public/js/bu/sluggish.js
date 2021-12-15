//only check one
$('.basic').on('change', function () {
    $('.basic').not(this).prop('checked', false);
});



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});



$('#sluggish').on('submit', function (e) {
    e.preventDefault();

    var select = ($(document.activeElement).val());

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var i = $("input:checked").val();

    var factory = $("#data0" + i).val();
    var number = $("#data1" + i).val();
    var name = $("#data2" + i).val();
    var format = $("#data3" + i).val();
    var unit = $("#data4" + i).val();
    var oldstock = $("#data6" + i).val();
    var amount = $("#data7" + i).val();
    var receive = $("#data9" + i).val();
    var title = [];
    for (var k = 0; k < 10; k++) {
        title[k] = $("#title" + k).val();
    }

    var record = []; {
        $('#sluggish :checkbox').each(function () {
            //if(values.indexOf($(this).val()) === -1){
            record.push($(this).val());
            // }
        });
    }

    var data0 = [];
    var data1 = [];
    var data2 = [];
    var data3 = [];
    var data4 = [];
    var data5 = [];
    var data6 = [];
    var data7 = [];
    var data8 = new Array();
    var data9 = [];

    for (let k = 0; k < record.length; k++) {
        var a = new Array();
        data8.push(a);
        data0.push($("#data0" + record[k]).val());
        data1.push($("#data1" + record[k]).val());
        data2.push($("#data2" + record[k]).val());
        data3.push($("#data3" + record[k]).val());
        data4.push($("#data4" + record[k]).val());
        data5.push($("#data5" + record[k]).val());
        data6.push($("#data6" + record[k]).val());
        data7.push($("#data7" + record[k]).val());
        $("#data8" + record[k]).children('span').each(function () {
            data8[data8.length - 1].push($(this).text());
        });
        data9.push($("#data9" + record[k]).val());
    }



    console.log(data6);
    console.log(data8);

    checked = $("input[type=checkbox]:checked").length;

    if (select == '提交' || select == 'Submit') {
        if (!checked) {
            alert(Lang.get('bupagelang.nocheck1'));
            return false;
        }

        if (amount === '') {

            document.getElementById("data7" + i).style.borderColor = "red";
            alert(Lang.get('bupagelang.enteramount'));
            return false;
        }
        if (receive === null) {
            document.getElementById("data9" + i).style.borderColor = "red";
            alert(Lang.get('bupagelang.enterfactory'));
            return false;
        }

        if (parseInt(amount) > parseInt(oldstock) || parseInt(amount) <= 0) {
            alert(Lang.get('bupagelang.amounterr'));
            return false;
        }
    }
    if (select == '提交' || select == 'Submit') {
        $.ajax({
            type: 'POST',

            url: "transsluggish",
            data: {
                factory: factory,
                number: number,
                name: name,
                format: format,
                unit: unit,
                oldstock: oldstock,
                amount: amount,
                receive: receive
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

            success: function (data) {
                var mess = Lang.get('bupagelang.dbadd') + ' ' + Lang.get('bupagelang.success') + ' ' +
                    Lang.get('bupagelang.dblist') + ' : ' + data.message;
                alert(mess);
                window.location.href = "/bu";
            },
            error: function (err) {
                //transaction error
                if (err.status == 420) {
                    var mess = myObj.message;
                    alert(mess);
                    window.location.reload();
                }
                //庫存異動
                else if (err.status == 421) {
                    var mess = Lang.get('bupagelang.inventoryerr') + ' ' + err.responseJSON.message;
                    alert(mess);
                    return false;
                }
            },
        });
    } else {

        $.ajax({
            type: 'POST',
            url: "download",
            data: {
                title: title,
                data0: data0,
                data1: data1,
                data2: data2,
                data3: data3,
                data4: data4,
                data5: data5,
                data6: data6,
                data7: data7,
                data8: data8,
                data9: data9,
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

$(window).on('load', function () {
    // PAGE IS FULLY LOADED
    // FADE OUT YOUR OVERLAYING DIV
    $('body').loadingModal('hide');
});
