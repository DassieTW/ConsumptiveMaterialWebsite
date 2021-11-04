
//show select 領料單號
$("#list").on("change", function () {
    var value = $("#list").val();
    $('#test').find('tr').not('#require').hide();
    var result_style = document.getElementById(value).style;
    result_style.display = 'table-row';
    //document.getElementById("test").style.display = "block";
});


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var othersend;
var otherpick;
$("#sendpeople").on("change", function () {

    var value = $("#sendpeople").val();
    if (value === "其他" || value === "other") {
        document.getElementById("inputsendpeople").style.display = "block";
        document.getElementById("inputsendpeople").required = true;
        othersend = true;
    }
    else {
        document.getElementById("inputsendpeople").style.display = "none";
        document.getElementById("inputsendpeople").required = false;
        othersend = false;
    }
});

$("#pickpeople").on("change", function () {

    var value = $("#pickpeople").val();
    if (value === "其他" || value === "other") {
        document.getElementById("inputpickpeople").style.display = "block";
        document.getElementById("inputpickpeople").required = true;
        otherpick = true;
    }
    else {
        document.getElementById("inputpickpeople").style.display = "none";
        document.getElementById("inputpickpeople").required = false;
        otherpick = false;
    }
});

$('#picklist').on('submit', function (e) {
    e.preventDefault();
    var count = $("#count").val();
    var list = $("#list").val();
    var advance = $("#advance" + list).html();
    var number = $("#number" + list).html();
    var client = $("#client" + list).html();
    var amount = $("#amount" + list).val();
    var reason = $("#reason" + list).val();
    var send = $("#sendpeople").val();
    var checkpeople = [];

    for(let i = 0 ; i < count ; i++)
    {
        checkpeople.push($("#checkpeople" + i).val());
    }
    console.log(checkpeople);
    var inputsendpeople = $("#inputsendpeople").val();
    var check1 = checkpeople.indexOf(inputsendpeople);
    var inputpickpeople = $("#inputpickpeople").val();
    var check2 = checkpeople.indexOf(inputpickpeople);

    send = send.split(' ');
    var sendpeople = send[0];
    var pick = $("#pickpeople").val();
    pick = pick.split(' ');
    var pickpeople = pick[0];

    if(othersend)
    {
        if(check1 == -1)
        {
            alert(Lang.get('outboundpageLang.nosendpeople'));
            return false;
        }
        else
        {
            sendpeople = ("#inputsendpeople").val();
        }
    }
    if(otherpick)
    {
        if(check2 == -1)
        {
            alert(Lang.get('outboundpageLang.nopickpeople'));
            return false;
        }
        else
        {
            pickpeople = ("#inputpickpeople").val();
        }
    }

    var position = $("#position" + list).val();
    if (position != null) {
        position = position.split(' ');
        position = position[0];
        position = position.split('儲位:');
        position = position[1];
    }
    else {
        document.getElementById("position" + list).style.borderColor = "red";
        alert(Lang.get('outboundpageLang.enterloc'));
        return false;
    }

    $.ajax({
        type: 'POST',
        url: "picklistsubmit",
        data: {
            list: list, advance: advance, amount: amount, reason: reason, sendpeople: sendpeople
            , pickpeople: pickpeople, position: position, number: number, client: client
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
            console.log(data.boolean);

            var mess = Lang.get('outboundpageLang.outpickok') + ' : ' + list;
                alert(mess);
                //alert("出庫完成，領料單號: " + list);
                window.location.href = "picklist";
                //window.location.href = "member.newok";

        },
        error: function (err) {
            //非月請購沒填安全庫存
            if (err.status == 420) {
                document.getElementById("reasonerror").style.display = "block";
                document.getElementById("reason" + list).style.borderColor = "red";
                document.getElementById("lessstock").style.display = "none";
                document.getElementById("amount" + list).style.borderColor = "";
                document.getElementById("position").style.borderColor = "";

            }
            //儲位庫存小於實際領用數量
            else if (err.status == 421) {
                document.getElementById("lessstock").style.display = "block";
                document.getElementById("position").style.borderColor = "red";
                $("#lessstock #position").html(Lang.get('outboundpageLang.nowloc') + ' : ' + err.responseJSON.position + '<br>' + Lang.get('outboundpageLang.stockless'));
                $("#lessstock #nowstock").html(Lang.get('outboundpageLang.nowstock') + ' : ' + err.responseJSON.nowstock);
                $("#lessstock #amount").html(Lang.get('outboundpageLang.realpickamount') + ' : ' + amount);
                document.getElementById("amount" + list).style.borderColor = "red";
                document.getElementById("reasonerror").style.display = "none";
                document.getElementById("reason" + list).style.borderColor = "";
            }
            //transcation error
            else if (err.status == 422) {
                window.location.reload();
            }
          },
    });
});


