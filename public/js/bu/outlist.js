
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#outlisttest').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var count = $("#count").val();
    var clients = new Array();
    var nowstocks = new Array();
    var realamounts = new Array();
    var positions = new Array();
    console.log(count);
    for (var i = 0; i < count; i++) {
        clients[i] = $("#data1" + i).val();
        nowstocks[i] = $("#data6" + i).val();
        realamounts[i] = $("#data7" + i).val();
        positions[i] = $("#data8" + i).val();
    }
    var list = $("#data0" + '0').val();
    var number = $("#data2" + '0').val();
    var name = $("#data3" + '0').val();
    var format = $("#data4" + '0').val();
    var preamount = $("#data5" + '0').val();
    var outfactory = $("#data9" + '0').val();
    var receivefac = $("#data10" + '0').val();

    var out = $("#outpeople").val();
    out = out.split(' ');
    var outpeople = out[5];

    var row = 0;
    var amount = 0;
    for (var i = 0; i < count; i++) {
        if (parseInt(realamounts[i]) > parseInt(nowstocks[i])) {
            row = i + 1;
            var mess = Lang.get('bupagelang.row') + ' : ' + row + ' ' + Lang.get('bupagelang.amounterr1');
            alert(mess);
            return false;
        }
        else {
            continue;
        }
    }
    for (var i = 0; i < count; i++) {
        amount = amount + parseInt(realamounts[i]);
    }

    var mess = Lang.get('bupagelang.preamount') + ' : ' + preamount + ' ' + Lang.get('bupagelang.realamount') + ' : ' + amount +
        ' ' + Lang.get('bupagelang.dblist') + ' : ' + list + '\n' + Lang.get('bupagelang.checktrans');
    var sure = window.confirm(mess);


    if (sure !== true) {
        return false;
    } else {
        $.ajax({
            type: 'POST',
            url: "outlistsubmit",
            data: {
                list: list, clients: clients, number: number, name: name, format, format, preamount :preamount , nowstocks: nowstocks,
                realamounts: realamounts, positions: positions, outfactory: outfactory, receivefac: receivefac, count: count, outpeople: outpeople
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
                var myObj = JSON.parse(data);
                console.log(myObj);

                if (myObj.boolean === true) {


                    var mess = Lang.get('bupagelang.dblist') + ' : ' + myObj.message + ' ' + Lang.get('bupagelang.changeok');
                    alert(mess);

                    window.location.href = "/bu";

                }

                else if (myObj.boolean === false) {

                    alert(myObj.message)
                    window.location.reload();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {

                console.warn(jqXHR.responseText);

                alert(errorThrown);
            }
        });
    }
});





