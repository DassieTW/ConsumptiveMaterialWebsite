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
    $('#inboundsearch').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();


        var titlecount = $("#titlecount").val();
        var count = $("#count").val();
        var titlename = $("#titlename").val();
        var title = [];
        for (let i = 0; i < titlecount; i++) {
            title.push($("#title" + i).val());
        }
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
        $.ajax({
            type: 'POST',
            url: "download",
            data: {
                title: title,
                titlecount: titlecount,
                titlename: titlename,
                AllData: JSON.stringify(data),
                count: count,
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
    });
});
