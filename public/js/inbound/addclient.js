function appenSVg(count) {
    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    var path = document.createElementNS("http://www.w3.org/2000/svg", 'path');
    path.setAttribute('d', "M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z");
    svg.setAttribute("width", "16");
    svg.setAttribute("height", "16");
    svg.setAttribute("fill", "#c94466");
    svg.setAttribute("class", "bi bi-x-circle-fill");
    svg.setAttribute("viewBox", "0 0 16 16");
    svg.appendChild(path);
    $('#deleteBtn' + count).append(svg);
    count = count + 1;
} // appenSVg

function copyoption(count) {
    var select1 = document.getElementById("copyloc");
    var select2 = document.getElementById("newposition" + count);
    select2.innerHTML = select1.innerHTML;
    console.log(1);
    //$('#position'+ index + 'option').clone().appendTo('#position' + count);
}

function deleteBtn(index) {
    notyf.error({
        message: Lang.get("inboundpageLang.delete") +
            Lang.get("inboundpageLang.success"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
            x: "right",
            y: "bottom",
        },
    });
    $("#deleteBtn" + index).parent().parent().remove();
    index = index - 1;
    // on delete btn click
}

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

var index = $("#count").val();
var count = $("#count").val();
count = parseInt(count);
index = parseInt(index);

$(document).ready(function () {
    $('#add').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var client = $("#client").val();
        var inreason = $("#inreason").val();
        var number = $("#number").val();
        if (inreason === "其他" || inreason === "other") {
            inreason = $('#reason').val();
        }
        var submit = buttonIndex;
        $.ajax({
            type: 'POST',
            url: "addnew",
            data: {
                client: client,
                inreason: inreason,
                number: number,
                submit: submit
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

                if (data.boolean == 'true') {

                    document.getElementById("notransit").style.display = "none";
                    sessionStorage.setItem('addclient', count + 1);

                    notyf.success({
                        message: Lang.get("inboundpageLang.add") + Lang.get("inboundpageLang.success"),
                        duration: 3000, //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom"
                        }
                    });
                    var tbl = document.getElementById("addclienttable");
                    var body = document.getElementById("addclientbody");
                    var row = document.createElement("tr");

                    let rowdelete = document.createElement('td');
                    rowdelete.innerHTML = "<a id=" + "deleteBtn" + count + " href=javascript:deleteBtn(" + count + ")></a>";

                    let rowclient = document.createElement('td');
                    rowclient.innerHTML = "<span id=" + "client" + count + ">" + data.client + "</span>";

                    let rownumber = document.createElement('td');
                    rownumber.innerHTML = "<span id=" + "number" + count + ">" + data.number + "</span>";

                    let rowname = document.createElement('td');
                    rowname.innerHTML = "<span id=" + "name" + count + ">" + data.name + "</span>";

                    let rowformat = document.createElement('td');
                    rowformat.innerHTML = "<span id=" + "format" + count + ">" + data.format + "</span>";

                    let rowunit = document.createElement('td');
                    rowunit.innerHTML = "<span id=" + "unit" + count + ">" + data.unit + "</span>";

                    let rowtransit = document.createElement('td');
                    rowtransit.innerHTML = "<span id=" + "transit" + count + ">" + data.transit + "</span>";

                    let rowstock = document.createElement('td');
                    rowstock.innerHTML = "<span id=" + "stock" + count + ">" + data.stock + "</span>";

                    let rowsafe = document.createElement('td');
                    rowsafe.innerHTML = "<span id=" + "safe" + count + ">" + data.safe + "</span>";

                    let rowamount = document.createElement('td');
                    rowamount.innerHTML = '<input id="amount' + count + '"' + 'type = "number"' + 'class = "form-control"' + 'min = "1"' + 'value = "1"' + 'style="width: 100px"' + '>';

                    let rowinreason = document.createElement('td');
                    rowinreason.innerHTML = "<span id=" + "inreason" + count + ">" + data.inreason + "</span>";

                    let rowpositions = document.createElement('td');
                    rowpositions.innerHTML = "<span id=" + "positions" + count + ">" + data.positions + "</span>";

                    let rowlocation = document.createElement("td");
                    rowlocation.innerHTML = '<select id="newposition' + count + '"' + 'class = "form-select form-select-lg"' + 'style="width: 150px"' + "required>";


                    row.appendChild(rowdelete);
                    row.appendChild(rowclient);
                    row.appendChild(rownumber);
                    row.appendChild(rowname);
                    row.appendChild(rowformat);
                    row.appendChild(rowunit);
                    row.appendChild(rowtransit);
                    row.appendChild(rowstock);
                    row.appendChild(rowsafe);
                    row.appendChild(rowamount);
                    row.appendChild(rowinreason);
                    row.appendChild(rowpositions);
                    row.appendChild(rowlocation);

                    body.appendChild(row);
                    tbl.appendChild(body);
                    appenSVg(count);
                    copyoption(count);

                    index = index + 1;
                    count = count + 1;

                } else if (data.boolean == 'false') {
                    window.location.href = "/inbound/addclient";
                }

            },
            error: function (err) {
                console.log(err);
                //不等於12
                if (err.status == 420) {
                    document.getElementById("numbererror").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('number').value = '';
                    document.getElementById("numbererror1").style.display = "none";
                    document.getElementById("notransit").style.display = "none";
                }
                //無料號
                else if (err.status == 421) {
                    document.getElementById("numbererror1").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('number').value = '';
                    document.getElementById("numbererror").style.display = "none";
                    document.getElementById("notransit").style.display = "none";
                }
                //在途量為0
                else if (err.status == 422) {
                    document.getElementById("notransit").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('client').classList.add("is-invalid");
                    document.getElementById('inreason').classList.add("is-invalid");
                    document.getElementById('inreason').value = '';
                    document.getElementById('number').value = '';
                    document.getElementById('client').value = '';
                    document.getElementById("numbererror1").style.display = "none";
                    document.getElementById("numbererror").style.display = "none";
                }
            }
        });
    });
    $('#addclient').on('submit', function (e) {
        e.preventDefault();

        if(index == 0)
        {

        }
        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();


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
        console.log(count);
        if (count === undefined) {
            alert(Lang.get('inboundpageLang.notransit'));
            window.location.reload();
            return false;
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
});
