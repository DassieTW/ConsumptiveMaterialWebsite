
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$("#usereason").on("change", function () {

    var value = $("#usereason").val();
    if (value === "其他" || value === "other") {
        document.getElementById("reason").style.display = "block";
        document.getElementById("reason").required = true;
    }
    else {
        document.getElementById("reason").style.display = "none";
        document.getElementById("reason").required = false;
    }
});
$('#pick').on('submit', function (e) {
    e.preventDefault();
    var client = $("#client").val();
    var machine = $("#machine").val();
    var production = $("#production").val();
    var line = $("#line").val();
    var usereason = $("#usereason").val();
    if (usereason === "其他" || usereason === "other") {
        usereason = $('#reason').val();
    }
    var number = $('#number').val();
    $.ajax({
        type: 'POST',
        url: "pickadd",
        data: { client: client, machine: machine, production: production, line: line, usereason: usereason, number: number },
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
            window.location.href = "pickaddok";
        },
        error: function (err) {
            //沒有庫存
            if (err.status == 420) {
                document.getElementById("nostock").style.display = "block";
                document.getElementById('number').style.borderColor = "red";
                document.getElementById('client').style.borderColor = "red";
                document.getElementById('number').value = '';
                document.getElementById('client').value = '';
                document.getElementById("numbererror1").style.display = "none";
                document.getElementById("numbererror").style.display = "none";
            }
            //沒有料號
            else if (err.status == 421) {
                document.getElementById("numbererror1").style.display = "block";
                document.getElementById('number').style.borderColor = "red";
                document.getElementById('number').value = '';
                document.getElementById("nostock").style.display = "none";
                document.getElementById("client").style.borderColor = "";
                document.getElementById("numbererror").style.display = "none";
            }
            //料號長度不為12
            else if (err.status == 422) {
                document.getElementById("numbererror").style.display = "block";
                document.getElementById('number').style.borderColor = "red";
                document.getElementById('number').value = '';
                document.getElementById("nostock").style.display = "none";
                document.getElementById("client").style.borderColor = "";
                document.getElementById("numbererror1").style.display = "none";
            }
        }
    });
});
