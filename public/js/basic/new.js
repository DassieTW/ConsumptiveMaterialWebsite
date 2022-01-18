sessionStorage.clear();

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

var index = 0;
var count = 0;

function appenSVg(index) {
    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    var path = document.createElementNS("http://www.w3.org/2000/svg", 'path');
    path.setAttribute('d', "M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z");
    svg.setAttribute("width", "16");
    svg.setAttribute("height", "16");
    svg.setAttribute("fill", "#c94466");
    svg.setAttribute("class", "bi bi-x-circle-fill");
    svg.setAttribute("viewBox", "0 0 16 16");
    svg.appendChild(path);
    $('#deleteBtn' + index).append(svg);
    $('#deleteBtn' + index).on('click', function (e) {
        e.preventDefault();
        notyf.success({
            message: Lang.get("basicInfoLang.delete") + Lang.get("basicInfoLang.success"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
                x: "right",
                y: "bottom"
            }
        });
        count = count - 1;
        $(this).parent().parent().remove();
    }); // on delete btn click
} // appenSVg


$(document).ready(function () {
    $("select").change(function () {
        var checkedValue = $("#month").val();
        if (checkedValue === "是" || checkedValue === "Yes") {
            $("#safe").attr("disabled", true);
        } else {
            $("#safe").attr("disabled", false);
        }
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
                sessionStorage.setItem('materialcount', index + 1);
                document.getElementById('numbererror').style.display = "none";
                document.getElementById('numbererror1').style.display = "none";
                document.getElementById('safeerror').style.display = "none";

                notyf.success({
                    message: Lang.get("basicInfoLang.add") + Lang.get("basicInfoLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });

                document.getElementById('materialadd').style.display = "block";
                var tbl = document.getElementById("materialaddtable");
                var body = document.getElementById("materialaddbody");
                var row = document.createElement("tr");

                row.setAttribute("id", "row" + index);

                let rowdelete = document.createElement('td');
                rowdelete.innerHTML = "<a id=" + "deleteBtn" + index + "></a>";

                let rownumber = document.createElement('td');
                rownumber.innerHTML = '<input id="number' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'oninput="if(value.length>12)value=value.slice(0,12)"' + 'style="width: 150px"' + 'value = "' + data.number + '"' + '" required>';

                let rowname = document.createElement('td');
                rowname.innerHTML = '<input id="name' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' + 'style="width: 150px"' +
                    'value = "' + data.name + '"' + '" required >';

                let rowformat = document.createElement('td');
                rowformat.innerHTML = '<input id="format' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.format + '"' + 'style="width: 150px"' + '" required >';

                let rowprice = document.createElement('td');
                rowprice.innerHTML = '<input id="price' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'step="0.00001"' + 'min="0"' + 'value = "' + data.price + '"' + 'style="width: 150px"' + '" required>';

                let rowmoney = document.createElement('td');
                rowmoney.innerHTML = '<input id="money' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.money + '"' + 'style="width: 150px"' + '" readonly >';

                let rowunit = document.createElement('td');
                rowunit.innerHTML = '<input id="unit' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.unit + '"' + 'style="width: 150px"' + '" required >';

                let rowmpq = document.createElement('td');
                rowmpq.innerHTML = '<input id="mpq' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.mpq + '"' + 'min="0"' + 'style="width: 150px"' + '" required >';

                let rowmoq = document.createElement('td');
                rowmoq.innerHTML = '<input id="moq' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.moq + '"' + 'min="0"' + 'style="width: 150px"' + '" required >';

                let rowlt = document.createElement('td');
                rowlt.innerHTML = '<input id="lt' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.lt + '"' + 'min="0"' + 'style="width: 150px"' + '" required >';

                let rowmonth = document.createElement('td');
                rowmonth.innerHTML = '<input id="month' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.month + '"' + 'style="width: 150px"' + '" readonly >';

                let rowgradea = document.createElement('td');
                rowgradea.innerHTML = '<input id="gradea' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.gradea + '"' + 'style="width: 150px"' + '" readonly >';


                let rowbelong = document.createElement('td');
                rowbelong.innerHTML = '<input id="belong' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.belong + '"' + 'style="width: 150px"' + '" readonly >';

                let rowsend = document.createElement('td');
                rowsend.innerHTML = '<input id="send' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.send + '"' + 'style="width: 150px"' + '" readonly >';

                let rowsafe = document.createElement('td');
                rowsafe.innerHTML = '<input id="safe' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.safe + '"' + 'min="0"' + 'style="width: 150px"' + '">';

                row.appendChild(rowdelete);
                row.appendChild(rownumber);
                row.appendChild(rowname);
                row.appendChild(rowformat);
                row.appendChild(rowprice);
                row.appendChild(rowmoney);
                row.appendChild(rowunit);
                row.appendChild(rowmpq);
                row.appendChild(rowmoq);
                row.appendChild(rowlt);
                row.appendChild(rowmonth);
                row.appendChild(rowgradea);
                row.appendChild(rowbelong);
                row.appendChild(rowsend);
                row.appendChild(rowsafe);

                body.appendChild(row);
                tbl.appendChild(body);
                appenSVg(index);

                index = index + 1;
                count = count + 1;

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

    $('#materialadd').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        console.log(count);

        if (count == 0) {
            notyf.open({
                type: 'warning',
                message: Lang.get('basicInfoLang.nodata'),
                duration: 3000, //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
            return false;
        }

        console.log(sessionStorage.getItem('materialcount'));

        var data = [];
        var number = [];
        var name = [];
        var format = [];
        var price = [];
        var unit = [];
        var money = [];
        var mpq = [];
        var moq = [];
        var lt = [];
        var gradea = [];
        var belong = [];
        var month = [];
        var send = [];
        var safe = [];
        var row = [];

        for (let i = 0; i < sessionStorage.getItem('materialcount'); i++) {
            if ($("#number" + i).val() !== null && $("#number" + i).val() !== '' && $("#number" + i).val() !== undefined) {
                number.push($("#number" + i).val());
                name.push($("#name" + i).val());
                format.push($("#format" + i).val());
                price.push($("#price" + i).val());
                unit.push($("#unit" + i).val());
                money.push($("#money" + i).val());
                mpq.push($("#mpq" + i).val());
                moq.push($("#moq" + i).val());
                lt.push($("#lt" + i).val());
                gradea.push($("#gradea" + i).val());
                belong.push($("#belong" + i).val());
                month.push($("#month" + i).val());
                send.push($("#send" + i).val());
                safe.push($("#safe" + i).val());
                row.push(i.toString());
            }
        }

        for (let i = 0; i < sessionStorage.getItem('materialcount'); i++) {
            if (gradea[i] === "Yes") gradea[i] = "是";
            if (gradea[i] === "No") gradea[i] = "否";
            if (month[i] === "Yes") month[i] = "是";
            if (month[i] === "No") month[i] = "否";
            if (belong[i] === "Unit consumption" || belong[i] === "单耗") belong[i] = "單耗";
            if (belong[i] === "Station") belong[i] = "站位";
        }

        data.push(number);
        data.push(name);
        data.push(format);
        data.push(price);
        data.push(money);
        data.push(unit);
        data.push(mpq);
        data.push(moq);
        data.push(lt);
        data.push(month);
        data.push(gradea);
        data.push(belong);
        data.push(send);
        data.push(safe);

        $.ajax({
            type: 'POST',
            url: "insertuploadmaterial",
            data: {
                AllData: JSON.stringify(data),
                count: count,
                row: row,
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

                var mess = Lang.get('basicInfoLang.total') + ' : ' + count + Lang.get('basicInfoLang.record') +
                    Lang.get('basicInfoLang.matsdata') + ' ， ' + Lang.get('basicInfoLang.success') + Lang.get('basicInfoLang.new') +
                    ' : ' + data.record + Lang.get('basicInfoLang.matsdata');

                alert(mess);

                var mess2 = Lang.get('basicInfoLang.yellowrepeat');

                alert(mess2);

                for (let i = 0; i < row.length; i++) {

                    var same = row.filter(function (v) {
                        return (data.check).indexOf(v) > -1
                    });
                    var diff = row.filter(function (v) {
                        return (data.check).indexOf(v) == -1
                    });
                }
                for (let i = 0; i < same.length; i++) {
                    $('#row' + same[i]).remove();
                    count = count - 1;
                }
                for (let i = 0; i < diff.length; i++) {

                    document.getElementById("row" + diff[i]).style.backgroundColor = "yellow";
                }
                // window.location.href = "/basic";
            },
            error: function (err) {
                console.log(err.status);
                //非月請購沒填安全庫存
                if (err.status == 422) {
                    var mess = Lang.get('basicInfoLang.row') + ' : ' + err.responseJSON.message + ' ' + Lang.get('basicInfoLang.safeerror');
                    window.alert(mess);
                    window.location.href = 'new';
                } else if (err.status == 423) {
                    window.alert(err.responseJSON.message);
                    console.log(err.responseJSON.message);
                } // else if
                //transaction error
                else {
                    var mess = err.responseJSON.message;
                    window.alert(mess);
                    window.location.reload();
                }
            },
        });
    });
});
