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

$('#addnew').on('submit', function (e) {
    e.preventDefault();
    var count = $("#count").val();
    var client = $("#client").val();
    var number = $("#number").val();
    var name = $("#name").val();
    var format = $("#format").val();
    var unit = $("#unit").val();
    var amount = $("#amount").val();
    var stock = $("#stock").val();
    var inamount = $("#inamount").val();
    var inreason = $("#inreason").val();
    var oldposition = $("#oldposition").val();
    var newposition = $("#newposition").val();
    var inpeo = $("#inpeople").val();
    inpeo = inpeo.split(' ');
    var inpeople = inpeo[0];
    var checkpeople = [];

    for (let i = 0; i < count; i++) {
        checkpeople.push($("#checkpeople" + i).val());
    }
    console.log(checkpeople);

    var check1 = checkpeople.indexOf(inpeople);

    if (check1 == -1) {
        alert(Lang.get("inboundpageLang.noinpeople"));
        $("#inpeople").css("border-color", "red");
        document.getElementById("nostock").style.display = "none";
        document.getElementById("inamount").style.borderColor = "";
        return false;
    }
    if (stock === null || stock === 0) stock = 'zero';
    $.ajax({
        type: 'POST',
        url: "addnewsubmit",
        data: {
            client: client,
            number: number,
            name: name,
            format: format,
            unit: unit,
            amount: amount,
            stock: stock,
            inamount: inamount,
            inreason: inreason,
            oldposition: oldposition,
            newposition: newposition,
            inpeople,
            inpeople
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
            var mess = Lang.get('inboundpageLang.add') + Lang.get('inboundpageLang.success') + ' : ' +
                Lang.get('inboundpageLang.inlist') + ' : ' + data.message;
            alert(mess);
            window.location.href = "/inbound/add";

        },
        error: function (err) {
            //入庫數量大於在途量
            if (err.status == 420) {
                document.getElementById("nostock").style.display = "block";
                document.getElementById("inamount").classList.add("is-invalid");
                $("#inpeople").css("border-color", "");
                return false;
                //transaction error
            } else if (err.status == 421) {
                window.location.reload();
            }
        }
    });
});
