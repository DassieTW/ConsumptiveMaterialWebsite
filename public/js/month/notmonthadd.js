sessionStorage.clear();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


var total = 1;
var count = 1;

function copyoption(count) {
    var select1 = document.getElementById("reason");
    var select2 = document.getElementById("reason" + count);
    select2.innerHTML = select1.innerHTML;
    console.log(1);
    //$('#position'+ index + 'option').clone().appendTo('#position' + count);
}

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
} // appenSVg

function deleteBtn(index) {
    notyf.success({
        message: Lang.get("outboundpageLang.delete") +
            Lang.get("outboundpageLang.success"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
            x: "right",
            y: "bottom",
        },
    });
    total = total - 1;
    $("#deleteBtn" + index).parent().parent().remove();
    // on delete btn click
}

$(document).ready(function () {
    $('#notmonth').on('submit', function (e) {

        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var client = $("#client").val();
        var number = $("#number").val();
        if (number.length != 12) {
            $("#number").addClass("is-invalid");
            $("#numbererror1").css("display", "block");
            return false;
        }
        $.ajax({
            type: 'POST',
            url: "notmonthadd",
            data: {
                client: client,
                number: number,
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

                document.getElementById('numbererror2').style.display = "none";
                document.getElementById('numbererror1').style.display = "none";

                notyf.success({
                    message: Lang.get("outboundpageLang.add") + Lang.get("outboundpageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });

                var tbl = document.getElementById("notmonthtable");
                var body = document.getElementById("notmonthbody");
                var row = document.createElement("tr");

                let rowdelete = document.createElement('td');
                rowdelete.innerHTML = "<a id=" + "deleteBtn" + count + "></a>";

                let rowclient = document.createElement('td');
                rowclient.innerHTML = "<span id=" + "client" + count + ">" + data.client + "</span>";

                let rownumber = document.createElement('td');
                rownumber.innerHTML = "<span id=" + "number" + count + ">" + data.number + "</span>";

                let rowamount = document.createElement('td');
                rowamount.innerHTML = '<input id="amount' + count + '"' + 'type = "number"' + 'class = "form-control amount"' +
                    'min = "1"' + "placeholder=" + Lang.get("monthlyPRpageLang.enteramount") + " required>";

                let rowname = document.createElement('td');
                rowname.innerHTML = "<span id=" + "name" + count + ">" + data.name + "</span>";

                let rowsxb = document.createElement('td');
                rowsxb.innerHTML = '<input id="sxb' + count + '"' + 'type = "text"' + 'class = "form-control"' +
                    "placeholder=" + Lang.get("monthlyPRpageLang.entersxb") + " required>";

                let rowsay = document.createElement('td');
                rowsay.innerHTML = '<input id="say' + count + '"' + 'type = "text"' + 'class = "form-control"' +
                    "placeholder=" + Lang.get("monthlyPRpageLang.enterdesc") + '>';

                let rowunit = document.createElement('td');
                rowunit.innerHTML = "<span id=" + "unit" + count + ">" + data.unit + "</span>";

                let rowmonth = document.createElement('td');
                rowmonth.innerHTML = "<span id=" + "month" + count + ">" + data.month + "</span>";

                let rowreason = document.createElement("td");
                rowreason.innerHTML = '<select id="reason' + count + '"' + 'class = "form-select form-select-lg"' + ">";

                row.appendChild(rowdelete);
                row.appendChild(rowclient);
                row.appendChild(rownumber);
                row.appendChild(rowamount);
                row.appendChild(rowsxb);
                row.appendChild(rowsay)
                row.appendChild(rowname);
                row.appendChild(rowunit);
                row.appendChild(rowmonth);
                row.appendChild(rowreason);
                body.appendChild(row);
                tbl.appendChild(body);
                copyoption(count);
                appenSVg(count);
                count = count + 1;
                total = total + 1;

            },
            error: function (err) {
                console.log(err);
                //無料號
                if (err.status == 421) {
                    document.getElementById("numbererror2").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('number').value = '';
                    document.getElementById("numbererror1").style.display = "none";
                }
                //transaction error
                else {
                    alert(err.responseJSON.message);
                    window.location.reload();
                }
            }
        });

    });
    $('#notmonthadd').on('submit', function (e) {
        e.preventDefault();


        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        if (total == 0) {
            alert('no data');
            return false;
        }

        var client = [];
        var number = [];
        var amount = [];
        var sxb = [];
        var say = [];
        var reason = [];
        var month = [];

        for (let i = 0; i < count; i++) {
            if ($("#client" + i).text() !== null && $("#client" + i).text() !== "") {
                client.push($("#client" + i).text());
                number.push($("#number" + i).text());
                month.push($("#month" + i).text());
                amount.push(parseInt($("#amount" + i).val()));
                sxb.push($("#sxb" + i).val());
                say.push($("#say" + i).val());
                reason.push($("#reason" + i).val());
            }
        }

        //check month write description
        for (let i = 0; i < count; i++) {
            if (month[i] == "是" && reason[i] == null || say[i] == "") {

                let row = i + 1;
                $("#errorrow").empty().append(Lang.get("monthlyPRpageLang.row") + " " + row);
                document.getElementById("error").style.display = "block";
                document.getElementById("reason" + i).classList.add("is-invalid");
                document.getElementById("say" + i).classList.add("is-invalid");
                return false;
            } else {
                document.getElementById("error").style.display = "none";
            }
        }

        $.ajax({
            type: 'POST',
            url: "notmonthsubmit",
            data: {
                client: client,
                number: number,
                amount: amount,
                say: say,
                sxb: sxb,
                reason: reason,
                month: month
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

                var mess = Lang.get('monthlyPRpageLang.total') + ' : ' + data.record + ' ' + Lang.get('monthlyPRpageLang.record') +
                    Lang.get('monthlyPRpageLang.notmonth') + Lang.get('monthlyPRpageLang.add') + Lang.get('monthlyPRpageLang.success');
                alert(mess);
                window.location.href = "importnotmonth";
                //window.location.href = "member.newok";

            },
            error: function (err) {
                //transaction error
                if (err.status == 421) {
                    console.log(err.status);
                    alert(err.responseJSON.message);
                    window.location.reload();
                }
            },
        });
    });
});
