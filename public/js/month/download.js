$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {

    $('#notmonthform').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var data = [];
        var data0 = [];
        var data1 = [];
        var data2 = [];
        var data3 = [];
        var data4 = [];
        var data5 = [];
        var data6 = [];

        var count = $("#count").val();
        var title = [];
        var titlename = $("#titlename").val();
        var titlecount = $("#titlecount").val();

        for (let i = 0; i < titlecount; i++) {
            title.push($("#title" + i).val());
        }

        for (let i = 0; i < count; i++) {
            data0.push($("#data0" + i).val());
            data1.push($("#data1" + i).val());
            data2.push($("#data2" + i).val());
            data3.push($("#data3" + i).val());
            data4.push($("#data4" + i).val());
            data5.push($("#data5" + i).val());
            data6.push($("#data6" + i).val());

        }


        data.push(data0);
        data.push(data1);
        data.push(data2);
        data.push(data3);
        data.push(data4);
        data.push(data5);
        data.push(data6);

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

    });

});
