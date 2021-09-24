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
    var time = [];
    for (var j = 0; j < 5; j++) {
        var count = $("#count" + j).val();
        time.push(count);
        console.log(count);
        if (count != 0) {
            k = k + 1;
            data8[j] = new Array();
            var record = $("#record" + j).val();
            data0.push($("#data0" + record).val());
            data1.push($("#data1" + record).val());
            data2.push($("#data2" + record).val());
            data3.push($("#data3" + record).val());
            data4.push($("#data4" + record).val());
            data5.push($("#data5" + record).val());
            data6.push($("#data6" + record).val());
            data7.push($("#data7" + record).val());
            $("#data8" + record).children('span').each(function () {
                data8[j].push($(this).text());
            });
            data9.push($("#data9" + record).val());
        }
    }



    checked = $("input[type=checkbox]:checked").length;

    if (select == '提交') {
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
            document.getElementById("data8" + i).style.borderColor = "red";
            alert(Lang.get('bupagelang.enterfactory'));
            return false;
        }

        if (parseInt(amount) > parseInt(oldstock) || parseInt(amount) <= 0) {
            alert(Lang.get('bupagelang.amounterr'));
            return false;
        }
    }
    if (select == '提交') {
        $.ajax({
            type: 'POST',

            url: "transsluggish",
            data: {
                factory: factory, number: number, name: name, format: format, unit: unit,
                oldstock: oldstock, amount: amount, receive: receive
            },

            success: function (data) {

                console.log(data);
                var myObj = JSON.parse(data);
                console.log(myObj);
                if (myObj.boolean === true && myObj.passbool === true) {
                    var mess = Lang.get('bupagelang.dbadd') + ' ' + Lang.get('bupagelang.success') + ' ' +
                        Lang.get('bupagelang.dblist') + ' : ' + myObj.message;
                    alert(mess);

                    window.location.href = "/bu";

                }

                else if (myObj.boolean === true && myObj.passbool === false) {
                    var mess = Lang.get('bupagelang.inventoryerr') + ' ' + myObj.message;

                    alert(mess);
                    //window.location.reload();

                }
                else {
                    var mess = myObj.message;
                    alert(mess);
                    //window.location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
                alert(errorThrown);
            }
        });
    }
    else {
        $.ajax({
            type: 'POST',
            url: "download",
            data: {
                title: title, data0: data0, data1: data1, data2: data2, data3: data3,
                data4: data4, data5: data5, data6: data6, data7: data7, data8: data8, data9: data9, time: time
            },
            xhrFields: {
                responseType: 'blob' // to avoid binary data being mangled on charset conversion
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
                            // console.log(decodeURIComponent(filename));
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


