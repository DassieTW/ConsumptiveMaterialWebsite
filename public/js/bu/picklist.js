$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function myFunction() {
    var input, filter, ul, li, a, i;
    ul = document.getElementById("pickmenu");
    li = ul.getElementsByTagName("a");
    input = document.getElementById("pickpeople");
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

$(".picklist").mouseover(function (e) {
    e.preventDefault();
    var ename = $(this).text();
    $("#pickpeople").val(ename);
});
$(".picklist").on("click", function (e) {
    e.preventDefault();
    var ename = $(this).text();
    $("#pickpeople").val(ename);
});

$("#pickpeople").on("focus", function () {
    $("#pickmenu").show();
});
$("#pickpeople").on("input", function () {
    $("#outmenu").show();
    myFunction();
});
$("#pickpeople").on("blur", function () {
    $("#pickmenu").hide();
});

$(document).ready(function () {

    $('#picklist').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var checkpeople = [];

        for (let i = 0; i < $("#checkcount").val(); i++) {
            checkpeople.push($("#checkpeople" + i).val());
        }

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
        var pickpeople = pick[0];

        if (parseInt(realout) != parseInt(realpick)) {
            var mess = Lang.get('bupagelang.realpickamount') + ' != ' + Lang.get('bupagelang.realamount');
            alert(mess);
            return false;
        }

        if (!sure) {
            var mess = Lang.get('bupagelang.noisn');
            alert(mess);
            window.location.href = "/basic/new";
        } else {
            var mess = Lang.get('bupagelang.realamount') + ' : ' + realout + ' ' + Lang.get('bupagelang.realpickamount') + ' : ' + realpick +
                ' ' + Lang.get('bupagelang.dblist') + ' : ' + list + '\n' + Lang.get('bupagelang.checkreceive');
            var confirm = window.confirm(mess);
        }

        var check1 = checkpeople.indexOf(pickpeople);

        //check has people
        if (check1 == -1) {
            alert(Lang.get("bupagelang.nopickpeople"));
            $("#pickpeople").addClass("is-invalid");
            return false;
        }

        if (confirm !== true) {
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: "picklistsubmit",
                data: {
                    list: list,
                    number: number,
                    realout: realout,
                    realpick: realpick,
                    pickpeople: pickpeople,
                    outfactory: outfactory,
                    receivefac: receivefac,
                    position: position,
                    client: client,
                    name: name,
                    format: format,
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
                    window.location.href = "picklist";

                },
                error: function (err) {
                    //transaction error
                    if (err.status == 420) {
                        console.log(err.status);
                        alert(err.responseJSON.message);
                        return false;
                    }
                    //inventory error
                    else if (err.status == 421) {
                        var mess = Lang.get('bupagelang.dblist') + ' : ' + list + ' ' + Lang.get('bupagelang.outfactory') + ' : ' +
                            outfactory + ' ' + Lang.get('bupagelang.inventoryerr');
                        alert(mess);
                        return false;
                    }
                },
            });
        }
    });
});
