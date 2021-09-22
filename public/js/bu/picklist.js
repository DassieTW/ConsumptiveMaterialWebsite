
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#picklist').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var sure = $("#sure").val();

    var list = $("#data0" + '0').val();
    var number = $("#data1" + '0').val();
    var name = $("#data2" + '0').val();
    var format = $("#data3" + '0').val();
    var realout = $("#data4" + '0').val();
    var realpick = $("#data5" + '0').val();
    var outfactory = $("#data6" + '0').val();
    var receivefac = $("#data7" + '0').val();
    var position = $("#position").val();
    var client = $("#client").val();

    var pick = $("#pickpeople").val();
    pick = pick.split(' ');
    var pickpeople = pick[5];

    if (parseInt(realout) != parseInt(realpick))
    {
        var mess = Lang.get('bupagelang.realpickamount') + ' != ' + Lang.get('bupagelang.realamount');
        alert(mess);
        return false;
    }

    if(!sure)
    {
        var mess = Lang.get('bupagelang.noisn');
        alert(mess);
        window.location.href = "/basic/new";
    }

    else
    {
        var mess = Lang.get('bupagelang.realamount') + ' : ' + realout + ' ' + Lang.get('bupagelang.realpickamount') + ' : ' + realpick +
            ' ' + Lang.get('bupagelang.dblist') + ' : ' + list + '\n' + Lang.get('bupagelang.checkreceive');
        var confirm = window.confirm(mess);
    }

    if (confirm !== true) {
        return false;
    } else {
        $.ajax({
            type: 'POST',
            url: "picklistsubmit",
            data: {
                list: list, number: number, realout :realout , realpick: realpick,pickpeople : pickpeople,
                outfactory: outfactory, receivefac: receivefac, position: position, client: client , name:name , format :format
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
                console.log(data);
                var myObj = JSON.parse(data);
                console.log(myObj);

                if (myObj.boolean === true && myObj.passbool === true) {


                    var mess = Lang.get('bupagelang.dblist') + ' : ' + myObj.message + ' ' + Lang.get('bupagelang.changeok');
                    alert(mess);

                    window.location.href = "/bu";

                }

                else if (myObj.boolean === true && myObj.passbool === false) {

                    var mess = Lang.get('bupagelang.dblist') + ' : ' + list + ' ' + Lang.get('bupagelang.outfactory') + ' : '
                    + outfactory + ' ' + Lang.get('bupagelang.inventoryerr');
                    alert(mess);

                    window.location.href = "/bu";
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

                console.warn(jqXHR.responseText);

                alert(errorThrown);
            }
        });
    }
});





