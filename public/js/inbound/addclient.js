$(".inboundlist").mouseover(function (e) {
    e.preventDefault();
    var ename = $(this).text();
    $("#inpeople").val(ename);
});
$(".inboundlist").on("click", function (e) {
    e.preventDefault();
    var ename = $(this).text();
    $("#inpeople").val(ename);
});

//show input data
function myFunction() {
    var input, filter, ul, li, a, i;
    ul = document.getElementById("inboundmenu");
    li = ul.getElementsByTagName("a");
    input = document.getElementById("inpeople");
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


$("#inpeople").on("focus", function () {
    $("#inboundmenu").show();
});
$("#inpeople").on("input", function () {
    $("#inboundmenu").show();
    myFunction();
});
$("#inpeople").on("blur", function () {
    $("#inboundmenu").hide();
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$('#addclient').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var count = $("#count").val();
    var checkcount = $("#checkcount").val();
    var client = [];
    var number = [];
    var buyamount = []; //在途量
    var amount = []; //入庫數量
    var position = [];
    var inreason = [];

    var inpeo = $("#inpeople").val();
    inpeo = inpeo.split(' ');
    var inpeople = inpeo[0];
    var checkpeople = [];

    //check inbound people exist
    for (let i = 0; i < checkcount; i++) {
        checkpeople.push($("#checkpeople" + i).val());
    }
    console.log(checkpeople);

    var check1 = checkpeople.indexOf(inpeople);

    if (check1 == -1) {
        alert(Lang.get("inboundpageLang.noinpeople"));
        $("#inpeople").css("border-color", "red");
        return false;
    }
    //

    for (let i = 0; i < count; i++) {
        client.push($("#client" + i).val());
        number.push($("#number" + i).val());
        buyamount.push($("#buyamount" + i).val());
        amount.push($("#amount" + i).val());
        position.push($("#position" + i).val());
        inreason.push($("#inreason" + i).val());
    }

    //入庫數量大於在途量
    for (let i = 0; i < count; i++) {
        if (parseInt(amount[i]) > parseInt(buyamount[i])) {
            row = i + 1;
            mess = Lang.get('inboundpageLang.transiterror') + ' ' + Lang.get('inboundpageLang.row') +
                ' : ' + row;
            alert(mess);
            $("#inpeople").css("border-color", "");
            return false;
        } else {
            continue;
        }
    }

    $.ajax({
        type: 'POST',
        url: "addclientsubmit",
        data: {
            client: client,
            number: number,
            buyamount: buyamount,
            amount: amount,
            inreason: inreason,
            position: position,
            inpeople: inpeople,
            count: count,
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

            var mess = Lang.get('inboundpageLang.total') + ' ' + (data.message) + ' ' + Lang.get('inboundpageLang.record') +
                Lang.get('inboundpageLang.change') + Lang.get('inboundpageLang.success') +
                Lang.get('inboundpageLang.inlist') + ' : ' + (data.opentime);
            alert(mess);
            window.location.href = "add";

        },
        error: function (err) {
            //transaction error
            if (err.status == 420) {
                console.log(err.status);
                var mess = err.responseJSON.message;
                alert(mess);
            }
        },
    });
});
