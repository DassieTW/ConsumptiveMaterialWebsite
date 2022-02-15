sessionStorage.clear();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

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
            message: Lang.get("monthlyPRpageLang.delete") + Lang.get("monthlyPRpageLang.success"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
                x: "right",
                y: "bottom"
            }
        });
        $(this).parent().parent().remove();
    }); // on delete btn click
} // appenSVg

$(document).ready(function () {

    $('#stand').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        if (sessionStorage.getItem("standcount") === null) {
            var index = 0;
        } else {
            var index = parseInt(sessionStorage.getItem('standcount'));
        }

        var client = $("#client").val();
        var number = $("#number").val();
        var production = $("#production").val();
        var machine = $("#machine").val();

        $.ajax({
            type: 'POST',
            url: "standnew",
            data: {
                client: client,
                number: number,
                production: production,
                machine: machine
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
                sessionStorage.setItem('standcount', index + 1);
                document.getElementById('numbererror').style.display = "none";
                document.getElementById('numbererror1').style.display = "none";

                notyf.success({
                    message: Lang.get("monthlyPRpageLang.add") + Lang.get("monthlyPRpageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });

                document.getElementById('standadd').style.display = "block";
                var tbl = document.getElementById("standaddtable");
                var body = document.getElementById("standaddbody");
                var row = document.createElement("tr");

                row.setAttribute("id", "row" + index);

                let rowdelete = document.createElement('td');
                rowdelete.innerHTML = "<a id=" + "deleteBtn" + index + "></a>";

                let rownumber = document.createElement('td');
                rownumber.innerHTML = "<span id=" + "number" + index + ">" + data.number + "</span>";

                let rowname = document.createElement('td');
                rowname.innerHTML = "<span id=" + "name" + index + ">" + data.name + "</span>";

                let rowunit = document.createElement('td');
                rowunit.innerHTML = "<span id=" + "unit" + index + ">" + data.unit + "</span>";

                let rowmpq = document.createElement('td');
                rowmpq.innerHTML = "<span id=" + "mpq" + index + ">" + data.mpq + "</span>";

                let rowlt = document.createElement('td');
                rowlt.innerHTML = "<span id=" + "lt" + index + ">" + data.lt + "</span>";

                let rownowpeople = document.createElement('td');
                rownowpeople.innerHTML = '<input id="nowpeople' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownowline = document.createElement('td');
                rownowline.innerHTML = '<input id="nowline' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownowclass = document.createElement('td');
                rownowclass.innerHTML = '<input id="nowclass' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownowuse = document.createElement('td');
                rownowuse.innerHTML = '<input id="nowuse' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownowchange = document.createElement('td');
                rownowchange.innerHTML = '<input id="nowchange' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownowdayneed = document.createElement('td');
                rownowdayneed.innerHTML = '<input id="nowdayneed' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'style="width: 150px"' + '" readonly>';

                let rownextpeople = document.createElement('td');
                rownextpeople.innerHTML = '<input id="nextpeople' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownextline = document.createElement('td');
                rownextline.innerHTML = '<input id="nextline' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownextclass = document.createElement('td');
                rownextclass.innerHTML = '<input id="nextclass' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownextuse = document.createElement('td');
                rownextuse.innerHTML = '<input id="nextuse' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownextchange = document.createElement('td');
                rownextchange.innerHTML = '<input id="nextchange' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000001"' + 'value = "0"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                let rownextdayneed = document.createElement('td');
                rownextdayneed.innerHTML = '<input id="nextdayneed' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'style="width: 150px"' + '" readonly>';

                let rowsafestock = document.createElement('td');
                rowsafestock.innerHTML = '<input id="safestock' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'style="width: 150px"' + '" readonly>';

                let rowclient = document.createElement('td');
                rowclient.innerHTML = "<span id=" + "client" + index + ">" + data.client + "</span>";

                let rowmachine = document.createElement('td');
                rowmachine.innerHTML = "<span id=" + "machine" + index + ">" + data.machine + "</span>";

                let rowproduction = document.createElement('td');
                rowproduction.innerHTML = "<span id=" + "production" + index + ">" + data.production + "</span>";

                row.appendChild(rowdelete);
                row.appendChild(rownumber);
                row.appendChild(rowname);
                row.appendChild(rowunit);
                row.appendChild(rowmpq);
                row.appendChild(rowlt);
                row.appendChild(rownowpeople);
                row.appendChild(rownowline);
                row.appendChild(rownowclass);
                row.appendChild(rownowuse);
                row.appendChild(rownowchange);
                row.appendChild(rownowdayneed);
                row.appendChild(rownextpeople);
                row.appendChild(rownextline);
                row.appendChild(rownextclass);
                row.appendChild(rownextuse);
                row.appendChild(rownextchange);
                row.appendChild(rownextdayneed);
                row.appendChild(rowsafestock);
                row.appendChild(rowclient);
                row.appendChild(rowmachine);
                row.appendChild(rowproduction);

                body.appendChild(row);
                tbl.appendChild(body);
                appenSVg(index);

                $("input").change(function () {
                    for (let i = 0; i < sessionStorage.getItem('standcount'); i++) {
                        var nowpeople = $("#nowpeople" + i).val();
                        var nowline = $("#nowline" + i).val();
                        var nowclass = $("#nowclass" + i).val();
                        var nowuse = $("#nowuse" + i).val();
                        var nowchange = $("#nowchange" + i).val();
                        var nextpeople = $("#nextpeople" + i).val();
                        var nextline = $("#nextline" + i).val();
                        var nextclass = $("#nextclass" + i).val();
                        var nextuse = $("#nextuse" + i).val();
                        var nextchange = $("#nextchange" + i).val();
                        var mpq = $("#mpq" + i).text();
                        var lt = $("#lt" + i).text();
                        var now = (nowpeople * nowclass * nowline * nowuse * nowchange) / mpq;
                        var next = (nextpeople * nextclass * nextline * nextuse * nextchange) / mpq;
                        var safe = next * lt;
                        now = now.toFixed(7);
                        next = next.toFixed(7);
                        safe = safe.toFixed(7);
                        $('#nowdayneed' + i).val(now);
                        $('#nextdayneed' + i).val(next);
                        $('#safestock' + i).val(safe);
                    }
                });
            },
            error: function (err) {
                //料號長度不為12
                if (err.status == 420) {
                    document.getElementById("numbererror1").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('number').value = '';
                    document.getElementById("numbererror").style.display = "none";

                }
                //料號不存在
                else if (err.status == 421) {
                    document.getElementById("numbererror").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('number').value = '';
                    document.getElementById("numbererror1").style.display = "none";
                }
            }
        });
    });

    $('#standadd').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        console.log(sessionStorage.getItem('standcount'));
        var row = [];
        var client = [];
        var machine = [];
        var production = [];
        var number = [];
        var nowpeople = [];
        var nowline = [];
        var nowclass = [];
        var nowuse = [];
        var nowchange = [];
        var nextpeople = [];
        var nextline = [];
        var nextclass = [];
        var nextuse = [];
        var nextchange = [];

        var jobnumber = $("#jobnumber").val();
        var email = $("#email").val() + "@pegatroncorp.com";
        var count = 0;

        for (let i = 0; i < sessionStorage.getItem('standcount'); i++) {
            if ($("#client" + i).text() !== null && $("#client" + i).text() !== '') {
                client.push($("#client" + i).text());
                machine.push($("#machine" + i).text());
                production.push($("#production" + i).text());
                number.push($("#number" + i).text());
                nowpeople.push($("#nowpeople" + i).val());
                nowline.push($("#nowline" + i).val());
                nowclass.push($("#nowclass" + i).val());
                nowuse.push($("#nowuse" + i).val());
                nowchange.push($("#nowchange" + i).val());
                nextpeople.push($("#nextpeople" + i).val());
                nextline.push($("#nextline" + i).val());
                nextclass.push($("#nextclass" + i).val());
                nextuse.push($("#nextuse" + i).val());
                nextchange.push($("#nextchange" + i).val());
                row.push(i.toString());
            }
        }

        if (client.length === 0) {
            notyf.open({
                type: 'warning',
                message: Lang.get('monthlyPRpageLang.nodata'),
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

        count = parseInt(client.length);

        $.ajax({
            type: 'POST',
            url: "standnewsubmit",
            data: {
                client: client,
                machine: machine,
                production: production,
                number: number,
                nowpeople: nowpeople,
                nowline: nowline,
                nowclass: nowclass,
                nowuse: nowuse,
                nowchange: nowchange,
                nextpeople: nextpeople,
                nextline: nextline,
                nextclass: nextclass,
                nextuse: nextuse,
                nextchange: nextchange,
                jobnumber: jobnumber,
                email: email,
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

                var mess = Lang.get('monthlyPRpageLang.total') + ' : ' + count + ' ' + Lang.get('monthlyPRpageLang.record') + ' ' +
                    Lang.get('monthlyPRpageLang.data') + ' ， ' + Lang.get('monthlyPRpageLang.success') + ' ' + Lang.get('monthlyPRpageLang.new') +
                    ' : ' + data.record + ' ' + Lang.get('monthlyPRpageLang.record') + ' ' + Lang.get('monthlyPRpageLang.stand');
                alert(mess);

                var mess2 = Lang.get('monthlyPRpageLang.yellowrepeat');

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

                // $("#standhead").hide();
                // $("#standbody").hide();
                // $("#standupload").hide();

                // $('#url').append(' URL : ' + '<a>http://127.0.0.1/month/teststand?' + data.database + '</a>');

            },
            error: function (err) {
                //transaction error
                if (err.status == 421) {
                    console.log(err.responseJSON.message);
                    window.location.reload();
                }
            },
        });
    });

    $('#loadstand').on('click', function (e) {
        e.preventDefault();
        var origin = parseInt(sessionStorage.getItem('standcount'));

        if (sessionStorage.getItem("standcount") === null) {
            var j = 0;
        } else {
            var j = origin;
        }


        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        $('loadstand').data('clicked', true);
        $.ajax({
            type: 'POST',
            url: "loadstand",
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

                $('#loadstand').remove();
                alldatas = JSON.parse(JSON.stringify(data.datas));

                if (alldatas.length === 0) {
                    notyf.open({
                        type: 'warning',
                        message: Lang.get('monthlyPRpageLang.noload'),
                        duration: 3000, //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom"
                        }
                    });
                } else {
                    notyf.open({
                        type: 'success',
                        message: Lang.get('monthlyPRpageLang.loadsuccess'),
                        duration: 3000, //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom"
                        }
                    });
                }

                if (sessionStorage.getItem("standcount") === null) {
                    sessionStorage.setItem('standcount', alldatas.length);
                } else {
                    var length = parseInt(alldatas.length) + parseInt(sessionStorage.getItem('standcount'));
                    sessionStorage.setItem('standcount', length);
                }

                document.getElementById('numbererror').style.display = "none";
                document.getElementById('numbererror1').style.display = "none";


                for (let i = 0; i < alldatas.length; i++) {



                    document.getElementById('standadd').style.display = "block";
                    var tbl = document.getElementById("standaddtable");
                    var body = document.getElementById("standaddbody");
                    var row = document.createElement("tr");

                    row.setAttribute("id", "row" + j);

                    let rowdelete = document.createElement('td');
                    rowdelete.innerHTML = "<a id=" + "deleteBtn" + j + "></a>";

                    let rownumber = document.createElement('td');
                    rownumber.innerHTML = "<span id=" + "number" + j + ">" + alldatas[i].料號 + "</span>";

                    let rowname = document.createElement('td');
                    rowname.innerHTML = "<span id=" + "name" + j + ">" + alldatas[i].品名 + "</span>";

                    let rowunit = document.createElement('td');
                    rowunit.innerHTML = "<span id=" + "unit" + j + ">" + alldatas[i].單位 + "</span>";

                    let rowmpq = document.createElement('td');
                    rowmpq.innerHTML = "<span id=" + "mpq" + j + ">" + alldatas[i].MPQ + "</span>";

                    let rowlt = document.createElement('td');
                    rowlt.innerHTML = "<span id=" + "lt" + j + ">" + alldatas[i].LT + "</span>";

                    let rownowpeople = document.createElement('td');
                    rownowpeople.innerHTML = '<input id="nowpeople' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].當月站位人數 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownowline = document.createElement('td');
                    rownowline.innerHTML = '<input id="nowline' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].當月開線數 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownowclass = document.createElement('td');
                    rownowclass.innerHTML = '<input id="nowclass' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].當月開班數 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownowuse = document.createElement('td');
                    rownowuse.innerHTML = '<input id="nowuse' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].當月每人每日需求量 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownowchange = document.createElement('td');
                    rownowchange.innerHTML = '<input id="nowchange' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].當月每日更換頻率 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownowdayneed = document.createElement('td');
                    rownowdayneed.innerHTML = '<input id="nowdayneed' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'style="width: 200px"' + '" readonly>';

                    let rownextpeople = document.createElement('td');
                    rownextpeople.innerHTML = '<input id="nextpeople' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].下月站位人數 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownextline = document.createElement('td');
                    rownextline.innerHTML = '<input id="nextline' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].下月開線數 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownextclass = document.createElement('td');
                    rownextclass.innerHTML = '<input id="nextclass' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].下月開班數 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownextuse = document.createElement('td');
                    rownextuse.innerHTML = '<input id="nextuse' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].下月每人每日需求量 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownextchange = document.createElement('td');
                    rownextchange.innerHTML = '<input id="nextchange' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'min = "0.0000001"' + 'value = "' + alldatas[i].下月每日更換頻率 + '"' + 'step = "0.0000001"' + 'style="width: 120px"' + '" required>';

                    let rownextdayneed = document.createElement('td');
                    rownextdayneed.innerHTML = '<input id="nextdayneed' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'style="width: 200px"' + '" readonly>';

                    let rowsafestock = document.createElement('td');
                    rowsafestock.innerHTML = '<input id="safestock' + j + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                        'style="width: 120px"' + '" readonly>';

                    let rowclient = document.createElement('td');
                    rowclient.innerHTML = "<span id=" + "client" + j + ">" + alldatas[i].客戶別 + "</span>";

                    let rowmachine = document.createElement('td');
                    rowmachine.innerHTML = "<span id=" + "machine" + j + ">" + alldatas[i].機種 + "</span>";

                    let rowproduction = document.createElement('td');
                    rowproduction.innerHTML = "<span id=" + "production" + j + ">" + alldatas[i].製程 + "</span>";

                    row.appendChild(rowdelete);
                    row.appendChild(rownumber);
                    row.appendChild(rowname);
                    row.appendChild(rowunit);
                    row.appendChild(rowmpq);
                    row.appendChild(rowlt);
                    row.appendChild(rownowpeople);
                    row.appendChild(rownowline);
                    row.appendChild(rownowclass);
                    row.appendChild(rownowuse);
                    row.appendChild(rownowchange);
                    row.appendChild(rownowdayneed);
                    row.appendChild(rownextpeople);
                    row.appendChild(rownextline);
                    row.appendChild(rownextclass);
                    row.appendChild(rownextuse);
                    row.appendChild(rownextchange);
                    row.appendChild(rownextdayneed);
                    row.appendChild(rowsafestock);
                    row.appendChild(rowclient);
                    row.appendChild(rowmachine);
                    row.appendChild(rowproduction);


                    body.appendChild(row);
                    tbl.appendChild(body);
                    appenSVg(j);

                    j = j + 1;
                    $("input").change(function () {
                        for (let i = 0; i < sessionStorage.getItem('standcount'); i++) {
                            var nowpeople = $("#nowpeople" + i).val();
                            var nowline = $("#nowline" + i).val();
                            var nowclass = $("#nowclass" + i).val();
                            var nowuse = $("#nowuse" + i).val();
                            var nowchange = $("#nowchange" + i).val();
                            var nextpeople = $("#nextpeople" + i).val();
                            var nextline = $("#nextline" + i).val();
                            var nextclass = $("#nextclass" + i).val();
                            var nextuse = $("#nextuse" + i).val();
                            var nextchange = $("#nextchange" + i).val();
                            var mpq = $("#mpq" + i).text();
                            var lt = $("#lt" + i).text();
                            var now = (nowpeople * nowclass * nowline * nowuse * nowchange) / mpq;
                            var next = (nextpeople * nextclass * nextline * nextuse * nextchange) / mpq;
                            var safe = next * lt;
                            now = now.toFixed(7);
                            next = next.toFixed(7);
                            safe = safe.toFixed(7);
                            $('#nowdayneed' + i).val(now);
                            $('#nextdayneed' + i).val(next);
                            $('#safestock' + i).val(safe);
                        }
                    });
                }

            },
            error: function (err) {},
        });

    }); // on load btn click
});
