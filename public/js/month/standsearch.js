var count = $("#count").val();
for(var i = 0 ; i < count ; i++){
    var nowpeople = $("#data8"+i).val();
    var nowline = $("#data9"+i).val();
    var nowclass = $("#data10"+i).val();
    var nowuse = $("#data11"+i).val();
    var nowchange = $("#data12"+i).val();
    var nextpeople = $("#data14"+i).val();
    var nextline = $("#data15"+i).val();
    var nextclass = $("#data16"+i).val();
    var nextuse = $("#data17"+i).val();
    var nextchange = $("#data18"+i).val();
    var mpq = $("#data3"+i).val();
    var lt = $("#data4"+i).val();
    var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
    var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
    var safe = next * lt ;
    $('#data13'+i).val(now);
    $('#data19'+i).val(next);
    $('#data20'+i).val(safe);
}

$(document).ready(function(){
    $("input").change(function(){

        for(var i = 0 ; i < count ; i++){
            var nowpeople = $("#data8"+i).val();
            var nowline = $("#data9"+i).val();
            var nowclass = $("#data10"+i).val();
            var nowuse = $("#data11"+i).val();
            var nowchange = $("#data12"+i).val();
            var nextpeople = $("#data14"+i).val();
            var nextline = $("#data15"+i).val();
            var nextclass = $("#data16"+i).val();
            var nextuse = $("#data17"+i).val();
            var nextchange = $("#data18"+i).val();
            var mpq = $("#data3"+i).val();
            var lt = $("#data4"+i).val();
            var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
            var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
            var safe = next * lt ;
            $('#data13'+i).val(now);
            $('#data19'+i).val(next);
            $('#data20'+i).val(safe);
        }

    });
  });




$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });



  $('#stand').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var select = ($(document.activeElement).val());


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
    $("input:checkbox[name=innumber]:checked").each(function(){
        check.push($(this).val());
    });

    if(select == "刪除" || select == "删除" || select == "Delete")
    {
        select = "刪除";
        var count = check.length;
    }
    if(select == "更新" || select == "Update")
    {
        select = "更新";
        var count = check.length;
    }
    if(select == "下載" || select == "下载" || select == "Download")
    {
        select = "下載";
        var count = $("#count").val();
    }
    if(select == "刪除" || select == "更新")
    {
        for(let i = 0 ; i < count ; i++)
        {
            data0.push($("#data0"+ check[i]).val());
            data1.push($("#data1"+ check[i]).val());
            data2.push($("#data2"+ check[i]).val());
            data3.push($("#data3"+ check[i]).val());
            data4.push($("#data4"+ check[i]).val());
            data5.push($("#data5"+ check[i]).val());
            data6.push($("#data6"+ check[i]).val());
            data7.push($("#data7"+ check[i]).val());
            data8.push($("#data8"+ check[i]).val());
            data9.push($("#data9"+ check[i]).val());
            data10.push($("#data10"+ check[i]).val());
            data11.push($("#data11"+ check[i]).val());
            data12.push($("#data12"+ check[i]).val());
            data13.push($("#data13"+ check[i]).val());
            data14.push($("#data14"+ check[i]).val());
            data15.push($("#data15"+ check[i]).val());
            data16.push($("#data16"+ check[i]).val());
            data17.push($("#data17"+ check[i]).val());
            data18.push($("#data18"+ check[i]).val());
            data19.push($("#data19"+ check[i]).val());
            data20.push($("#data20"+ check[i]).val());
            data21.push($("#data21" + check[i]).html());
        }
    }
    else
    {
        for(let i = 0 ; i < count ; i++)
        {
            data0.push($("#data0"+ [i]).val());
            data1.push($("#data1"+ [i]).val());
            data2.push($("#data2"+ [i]).val());
            data3.push($("#data3"+ [i]).val());
            data4.push($("#data4"+ [i]).val());
            data5.push($("#data5"+ [i]).val());
            data6.push($("#data6"+ [i]).val());
            data7.push($("#data7"+ [i]).val());
            data8.push($("#data8"+ [i]).val());
            data9.push($("#data9"+ [i]).val());
            data10.push($("#data10"+ [i]).val());
            data11.push($("#data11"+ [i]).val());
            data12.push($("#data12"+ [i]).val());
            data13.push($("#data13"+ [i]).val());
            data14.push($("#data14"+ [i]).val());
            data15.push($("#data15"+ [i]).val());
            data16.push($("#data16"+ [i]).val());
            data17.push($("#data17"+ [i]).val());
            data18.push($("#data18"+ [i]).val());
            data19.push($("#data19"+ [i]).val());
            data20.push($("#data20"+ [i]).val());
            data21.push($("#data21" + [i]).html());
        }
    }
    for(let i = 0 ; i < 22 ; i++)
    {
        title.push($("#title"+ [i]).val());
    }

    checked = $("input[type=checkbox]:checked").length;




    if(select == "刪除" || select == "删除" || select == "Delete")
    {
        select = "刪除";
    }
    if(select == "更新" || select == "Update")
    {
        if(!jobnumber)
        {
            alert(Lang.get('monthlyPRpageLang.nopeople'));
            return false;
        }
        else if(!email)
        {
            alert(Lang.get('monthlyPRpageLang.noemail'));
            return false;
        }
        select = "更新";
    }
    if(select == "刪除" || select == "更新")
    {
        if(!checked) {
            alert(Lang.get('monthlyPRpageLang.nocheck'));
            return false;
        }
        $.ajax({
        type: 'POST',
        url: "standchangeordelete",
        data: { data0: data0, data1: data1, data2: data2, data3: data3 , data4 : data4 , data5 :data5,
                data6 :data6 , data7 :data7 , data8:data8 , data9:data9 , data10 : data10 , data11 : data11 , data12 : data12,
                data13 : data13 , data14 : data14 , data15 : data15 , data16 : data16 , data17 : data17 , data18 : data18,
                data19 : data19 , data20:data20 , data21 : data21 , select : select , count : count , title : title , titlename : titlename,
                jobnumber : jobnumber , email : email},
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
            console.log(data);
            var myObj = JSON.parse(data);
            console.log(myObj);
            if (myObj.boolean === true && myObj.passbool === true ) {

                var mess = Lang.get('monthlyPRpageLang.total')+(myObj.message)+Lang.get('monthlyPRpageLang.record')
                    + Lang.get('monthlyPRpageLang.stand')
                    + Lang.get('monthlyPRpageLang.delete')+Lang.get('monthlyPRpageLang.success');
                alert(mess);
                window.location.href = "/month";
            }
            else if (myObj.boolean === true && myObj.passbool === false ) {

                var mess = Lang.get('monthlyPRpageLang.total')+(myObj.message)+Lang.get('monthlyPRpageLang.record')
                    + Lang.get('monthlyPRpageLang.stand')
                    + Lang.get('monthlyPRpageLang.change')+Lang.get('monthlyPRpageLang.submit')+Lang.get('monthlyPRpageLang.success');
                alert(mess);
                $("#standbody").hide();
                $('#url').append('  URL : ' + '<a>http://127.0.0.1/month/teststand?'+ myObj.database +'</a>');


            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.warn(jqXHR.responseText);
            alert(errorThrown);
        }
        });

    }

    else
    {
    $.ajax({
        type: 'POST',
        url: "standdownload",
        data: { data0: data0, data1: data1, data2: data2, data3: data3 , data4 : data4 , data5 :data5,
            data6 :data6 , data7 :data7 , data8:data8 , data9:data9 , data10 : data10 , data11 : data11 , data12 : data12,
            data13 : data13 , data14 : data14 , data15 : data15 , data16 : data16 , data17 : data17 , data18 : data18,
            data19 : data19 , data20:data20 , data21 : data21 , select : select , count : count , title : title , titlename : titlename,
            jobnumber : jobnumber , email : email},
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
