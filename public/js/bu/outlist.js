$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function myFunction() {
    var input, filter, ul, li, a, i;
    ul = document.getElementById("outmenu");
    li = ul.getElementsByTagName("a");
    input = document.getElementById("outpeople");
    filter = input.value.toUpperCase();
    for (i = 0; i < li.length; i++) {
        a = li[i];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";
        }
    }
}

$(".outlist").mouseover(function (e) {
    e.preventDefault();
    var ename = $(this).text();
    $("#outpeople").val(ename);
});
$(".outlist").on("click", function (e) {
    e.preventDefault();
    var ename = $(this).text();
    $("#outpeople").val(ename);
});

$("#outpeople").on("focus", function () {
    $("#outmenu").show();
});
$("#outpeople").on("input", function () {
    $("#outmenu").show();
    myFunction();
});
$("#outpeople").on("blur", function () {
    $("#outmenu").hide();
});

$(document).ready(function () {
    $('#outlist').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var checkpeople = [];

        for (let i = 0; i < $("#checkcount").val(); i++) {
            checkpeople.push($("#checkpeople" + i).val());
        }

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
        var outpeople = out[0];

        var row = 0;
        var amount = 0;

        for (let i = 0; i < $("#checkcount").val(); i++) {
            checkpeople.push($("#checkpeople" + i).val());
        }

        var check1 = checkpeople.indexOf(outpeople);

        for (var i = 0; i < count; i++) {
            if (parseInt(realamounts[i]) > parseInt(nowstocks[i])) {
                row = i + 1;
                var mess = Lang.get('bupagelang.row') + ' : ' + row + ' ' + Lang.get('bupagelang.amounterr1');
                alert(mess);
                return false;
            } else {
                continue;
            }
        }
        for (var i = 0; i < count; i++) {
            amount = amount + parseInt(realamounts[i]);
        }

        var mess = Lang.get('bupagelang.preamount') + ' : ' + preamount + ' ' + Lang.get('bupagelang.realamount') + ' : ' + amount +
            ' ' + Lang.get('bupagelang.dblist') + ' : ' + list + '\n' + Lang.get('bupagelang.checktrans');
        var sure = window.confirm(mess);

        //check has people
        if (check1 == -1) {
            alert(Lang.get("bupagelang.nooutpeople"));
            $("#outpeople").addClass("is-invalid");
            return false;
        }

        if (sure !== true) {
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: "outlistsubmit",
                data: {
                    list: list,
                    clients: clients,
                    number: number,
                    name: name,
                    format: format,
                    preamount: preamount,
                    nowstocks: nowstocks,
                    realamounts: realamounts,
                    positions: positions,
                    outfactory: outfactory,
                    receivefac: receivefac,
                    count: count,
                    outpeople: outpeople,
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

                    var mess = Lang.get('bupagelang.dblist') + ' : ' + data.message + ' ' + Lang.get('bupagelang.changeok');
                    alert(mess);
                    window.location.href = "outlist";

                },
                error: function (err) {
                    //transaction error
                    if (err.status == 420) {
                        console.log(err.status);
                        alert(err.responseJSON.message);
                    }
                },
            });
        }
    });
});
