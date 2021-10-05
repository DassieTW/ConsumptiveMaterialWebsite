//only check one
$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
});


var count = $("#count").val();


for(var i = 0 ; i < count ; i++){
    var status = $("#data1"+i).val();
    if(status == "已完成")
    {
        document.getElementById("check"+i).setAttribute('disabled', 'disabled');
        //document.getElementById("list"+i).style.backgroundColor = "black";
    }
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$('#bulist').on('submit', function (e) {
    e.preventDefault();

    var select = ($(document.activeElement).val());

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var i = $( "input:checked" ).val();

    var list = $("#data0"+i).val();
    checked = $("input[type=checkbox]:checked").length;

    var record = [];
    var title = [];

    for (var k = 0; k < 17; k++) {
        title[k] = $("#title" + k).val();
    }

    {
      $('#bulist :checkbox').each(function() {
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
    var data8 = [];
    var data9 = [];
    var data10 = [];
    var data11 = [];
    var data12 = [];
    var data13 = [];
    var data14 = [];
    var data15 = [];
    var data16 = [];

    for(let k = 0 ; k < record.length ; k ++)
    {
        data0.push($("#data0" + record[k]).val());
        data1.push($("#data1" + record[k]).val());
        data2.push($("#data2" + record[k]).val());
        data3.push($("#data3" + record[k]).val());
        data4.push($("#data4" + record[k]).val());
        data5.push($("#data5" + record[k]).val());
        data6.push($("#data6" + record[k]).val());
        data7.push($("#data7" + record[k]).val());
        data8.push($("#data8" + record[k]).val());
        data9.push($("#data9" + record[k]).val());
        data10.push($("#data10" + record[k]).val());
        data11.push($("#data11" + record[k]).val());
        data12.push($("#data12" + record[k]).val());
        data13.push($("#data13" + record[k]).val());
        data14.push($("#data14" + record[k]).val());
        data15.push($("#data15" + record[k]).val());
        data16.push($("#data16" + record[k]).val());

    }

    console.log(title);
    if(select == "刪除" || select == 'Delete'  || select == '删除')
    {
        if(!checked) {
            alert(Lang.get('bupagelang.nocheck'));
            return false;
        }
    }
    if(select == "刪除" || select == 'Delete' || select == '删除')
    {
        $.ajax({
            type:'POST',
            url:"delete",
            data:{list:list},
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
            success:function(data){
            console.log(data);
            var myObj = JSON.parse(data);
            console.log(myObj);
            if(myObj.boolean === true){
                var mess = Lang.get('bupagelang.delete') + ' ' + Lang.get('bupagelang.dblist') +' : '+ myObj.message + ' ' +Lang.get('bupagelang.success');
                alert(mess);

                window.location.href = "/bu";

            }

            else if(myObj.boolean === false){

                window.location.reload();
            }
            },
        error : function(jqXHR,textStatus,errorThrown){
            console.warn(jqXHR.responseText);
            alert(errorThrown);
        }
        });
    }
    else{
        $.ajax({
            type: 'POST',
            url: "downloadlist",
            data: {
                title: title, data0: data0, data1: data1, data2: data2, data3: data3,
                data4: data4, data5: data5, data6: data6, data7: data7, data8: data8, data9: data9,
                data10: data10, data11: data11, data12: data12, data13: data13,
                data14: data14, data15: data15, data16: data16,
            },
            xhrFields: {
                responseType: 'blob' // to avoid binary data being mangled on charset conversion
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
