//only check one
$('.basic').on('change', function () {
    $('.basic').not(this).prop('checked', false);
});



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(window).on('load', function () {
    // PAGE IS FULLY LOADED
    // FADE OUT YOUR OVERLAYING DIV
    $('body').loadingModal('hide');
    $('body').loadingModal('destroy');
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
            txtValue = $(this).find("input[id^='datab']").val();
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


    $('#sluggish').on('submit', function (e) {

        e.preventDefault();
        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();


        var title = [];
        for (var k = 0; k < 10; k++) {
            title[k] = $("#title" + k).val();
        }

        var record = []; {
            $('.basic').each(function () {
                record.push($(this).val());
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
            data0.push($("#dataa" + record[k]).val());
            data1.push($("#datab" + record[k]).val());
            data2.push($("#datac" + record[k]).val());
            data3.push($("#datad" + record[k]).val());
            data4.push($("#datae" + record[k]).val());
            data5.push($("#dataf" + record[k]).val());
            data6.push($("#datag" + record[k]).val());
            data7.push($("#datah" + record[k]).val());
            $("#datai" + record[k]).children('span').each(function () {
                data8[data8.length - 1].push($(this).text());
            });
            data9.push($("#dataj" + record[k]).val());
        }

        var data = [];

        var count = data0.length;
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

        $.ajax({
            type: 'POST',
            url: "download",
            data: {
                AllData: JSON.stringify(data),
                title: title,
                count: count,
            },
            xhrFields: {
                responseType: 'blob', // to avoid binary data being mangled on charset conversion
            },
            //dataType: 'json', // let's set the expected response format  // test
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

    });

    $('.basic').on('click', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var i = $(this).val();

        var factory = $("#dataa" + i).val();
        var number = $("#datab" + i).val();
        var name = $("#datac" + i).val();
        var format = $("#datad" + i).val();
        var unit = $("#datae" + i).val();
        var oldstock = $("#datag" + i).val();
        var amount = $("#datah" + i).val();
        var receive = $("#dataj" + i).val();

        if (amount === '') {
            document.getElementById("datah" + i).classList.add("is-invalid");
            notyf.open({
                type: 'warning',
                message: Lang.get('bupagelang.enteramount'),
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
        if (receive === null) {
            document.getElementById("dataj" + i).classList.add("is-invalid");
            notyf.open({
                type: 'warning',
                message: Lang.get('bupagelang.enterfactory'),
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

        if (parseInt(amount) > parseInt(oldstock) || parseInt(amount) <= 0) {
            document.getElementById("datah" + i).classList.add("is-invalid");
            notyf.open({
                type: 'warning',
                message: Lang.get('bupagelang.amounterr'),
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
            dataType: 'json', // let's set the expected response format
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
                var mess = Lang.get('bupagelang.dbadd') + ' ' + Lang.get('bupagelang.success') + ' ' +
                    Lang.get('bupagelang.dblist') + ' : ' + data.message;
                alert(mess);
                window.location.href = "/bu";
            },
            error: function (err) {
                //transaction error
                if (err.status == 420) {
                    var mess = err.responseJSON.message;
                    alert(mess);
                    window.location.reload();
                }
                //庫存異動
                else if (err.status == 421) {
                    var mess = Lang.get('bupagelang.inventoryerr') + ' ' + err.responseJSON.message;
                    alert(mess);
                    return false;
                } else {
                    var mess = err.responseJSON.message;
                    alert(mess);
                }
            },
        });
    });
});
