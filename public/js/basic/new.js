$(document).ready(function () {
    $("select").change(function () {
        var checkedValue = $("#month").val();
        if (checkedValue === "是" || checkedValue === "Yes") {
            $("#safe").attr("disabled", true);
        } else {
            $("#safe").attr("disabled", false);
        }
    });
});
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$("#newmaterial").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var number = $("#number").val();
    var name = $("#name").val();
    var format = $("#format").val();
    var price = $("#price").val();
    var unit = $("#unit").val();
    var money = $("#money").val();
    var mpq = $("#mpq").val();
    var moq = $("#moq").val();
    var lt = $("#lt").val();
    var gradea = $("#gradea").val();
    var belong = $("#belong").val();
    var month = $("#month").val();
    var send = $("#send").val();
    var safe = $("#safe").val();
    if (gradea === "Yes") gradea = "是";
    if (gradea === "No") gradea = "否";
    if (month === "Yes") month = "是";
    if (month === "No") month = "否";
    if (belong === "Unit consumption" || belong === "单耗") belong = "單耗";
    if (belong === "Station") belong = "站位";

    $.ajax({
        type: "POST",
        url: "new",
        data: {
            number: number,
            name: name,
            format: format,
            price: price,
            unit: unit,
            money: money,
            mpq: mpq,
            moq: moq,
            lt: lt,
            gradea: gradea,
            belong: belong,
            month: month,
            send: send,
            safe: safe,
        },

        beforeSend: function () {
            // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });
        },
        complete: function () {
            $("body").loadingModal("hide");
        },
        success: function (data) {
            console.log(data);

            //if(safe == null) safe = 'zero';
            var mess =
                Lang.get("basicInfoLang.newMats") +
                " " +
                Lang.get("basicInfoLang.success");
            alert(mess);
            //alert('New Material successfully');
            window.location.href = "/basic";

        },
        error: function (err) {
            //料號重複
            if (err.status == 420) {
                document.getElementById("numbererror").style.display = "block";
                document.getElementById('number').classList.add("is-invalid");
                document.getElementById('number').value = '';
                document.getElementById("numbererror1").style.display = "none";
                document.getElementById("safeerror").style.display = "none";
            }
            //料號長度不為12
            else if (err.status == 421) {
                document.getElementById("numbererror1").style.display = "block";
                document.getElementById('number').classList.add("is-invalid");
                document.getElementById('number').value = '';
                document.getElementById("numbererror").style.display = "none";
                document.getElementById("safeerror").style.display = "none";
            }
            //非月請購沒安全庫存
            else if (err.status == 422) {
                document.getElementById("safeerror").style.display = "block";
                document.getElementById('safe').classList.add("is-invalid");
                document.getElementById('safe').value = '';
                document.getElementById("numbererror1").style.display = "none";
                document.getElementById("numbererror").style.display = "none";

            }
        }
    });
});
