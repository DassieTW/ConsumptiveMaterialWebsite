sessionStorage.clear();
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
    } else {
        document.getElementById("reason").style.display = "none";
        document.getElementById("reason").required = false;
    }
});

var index = 0;

$('#pick').on('submit', function (e) {
    e.preventDefault();

    var select = ($(document.activeElement).val());
    var client = $("#client").val();
    var machine = $("#machine").val();
    var production = $("#production").val();
    var line = $("#line").val();
    var usereason = $("#usereason").val();
    if (usereason === "其他" || usereason === "other") {
        usereason = $('#reason').val();
    }
    var number = $('#number').val();

    if (select == '添加' || select == 'Add') {

        $.ajax({
            type: 'POST',
            url: "pickadd",
            data: {
                client: client,
                machine: machine,
                production: production,
                line: line,
                usereason: usereason,
                number: number
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

                sessionStorage.setItem('number' + index, data.number);
                sessionStorage.setItem('name' + index, data.name);
                sessionStorage.setItem('format' + index, data.format);
                sessionStorage.setItem('unit' + index, data.unit);
                sessionStorage.setItem('send' + index, data.send);
                sessionStorage.setItem('client' + index, data.client);
                sessionStorage.setItem('machine' + index, data.machine);
                sessionStorage.setItem('production' + index, data.production);
                sessionStorage.setItem('line' + index, data.line);
                sessionStorage.setItem('usereason' + index, data.usereason);
                index = index + 1;
                sessionStorage.setItem('pickcount', index);
                document.getElementById("nostock").style.display = "none";
                document.getElementById('client').style.borderColor = "";
                document.getElementById('number').style.borderColor = "";
                document.getElementById('numbererror').style.display = "none";
                document.getElementById('numbererror1').style.display = "none";

                notyf.success({
                    message: Lang.get("outboundpageLang.add") + Lang.get("outboundpageLang.success"),
                    duration: 5000,   //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });


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
    } else {
        e.preventDefault();

        var pickcount = sessionStorage.getItem('pickcount');
        if (pickcount > 0) {
            $.ajax({
                url: "pickaddok",
                type: "post",
                data: {
                    pickcount: pickcount
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
                    window.location.href = "outbound/pickaddok";

                },
                error: function (err) {
                    //非月請購沒填安全庫存
                    if (err.status == 420) {
                      var mess = err.responseJSON.row +' '+ err.responseJSON.message;
                      alert(mess);

                      console.log(err.responseJSON.message);
                      console.log(err.status);
                      //transaction error
                    } else if (err.status == 409) {
                        console.log(err.status);
                    }
                  },

            });

        }
        else{
            var mess = 'please add first'
            alert(mess);
        }
    }
});
