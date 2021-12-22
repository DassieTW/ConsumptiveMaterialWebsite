sessionStorage.clear();

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
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
            message: Lang.get("monthlyPRpageLang.delete") + Lang.get("monthlyPRpageLang.success"),
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

    $('#consume').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var client = $("#client").val();
        var number = $("#number").val();
        var production = $("#production").val();
        var machine = $("#machine").val();

        $.ajax({
            type: 'POST',
            url: "consumenew",
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
                sessionStorage.setItem('consumecount', index + 1);
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

                document.getElementById('consumeadd').style.display = "block";
                var tbl = document.getElementById("consumeaddtable");
                var body = document.getElementById("consumeaddbody");
                var row = document.createElement("tr");

                row.setAttribute("id", "row" + index);

                let rowdelete = document.createElement('td');
                rowdelete.innerHTML = "<a id=" + "deleteBtn" + index + "></a>";

                let rownumber = document.createElement('td');
                rownumber.innerHTML = "<span id=" + "number" + index + ">" + data.number + "</span>";

                let rowname = document.createElement('td');
                rowname.innerHTML = "<span id=" + "name" + index + ">" + data.name + "</span>";

                let rowformat = document.createElement('td');
                rowformat.innerHTML = "<span id=" + "format" + index + ">" + data.format + "</span>";

                let rowunit = document.createElement('td');
                rowunit.innerHTML = "<span id=" + "unit" + index + ">" + data.unit + "</span>";

                let rowlt = document.createElement('td');
                rowlt.innerHTML = "<span id=" + "lt" + index + ">" + data.lt + "</span>";

                let rowamount = document.createElement('td');
                rowamount.innerHTML = '<input id="amount' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'min = "0.0000000001"' + 'value = "0"' + 'step = "0.0000000001"' + 'style="width: 200px"' + '">';

                let rownowneed = document.createElement('td');
                rownowneed.innerHTML = '<input id="nowneed' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'style="width: 200px"' + '" readonly>';

                let rownextneed = document.createElement('td');
                rownextneed.innerHTML = '<input id="nextneed' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'style="width: 200px"' + '" readonly>';

                let rowsafestock = document.createElement('td');
                rowsafestock.innerHTML = '<input id="safestock' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'style="width: 200px"' + '" readonly>';

                let rowclient = document.createElement('td');
                rowclient.innerHTML = "<span id=" + "client" + index + ">" + data.client + "</span>";

                let rowmachine = document.createElement('td');
                rowmachine.innerHTML = "<span id=" + "machine" + index + ">" + data.machine + "</span>";

                let rowproduction = document.createElement('td');
                rowproduction.innerHTML = "<span id=" + "production" + index + ">" + data.production + "</span>";

                let rownowmps = document.createElement('td');
                rownowmps.innerHTML = '<input id="nowmps' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'step = "0.01"' + 'min = "0"' + 'style="width: 85px"' + 'value="' + data.nowmps + '">';

                let rownowday = document.createElement('td');
                rownowday.innerHTML = '<input id="nowday' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'step = "0.01"' + 'min = "0"' + 'style="width: 85px"' + 'value="' + data.nowday + '">';

                let rownextmps = document.createElement('td');
                rownextmps.innerHTML = '<input id="nextmps' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'step = "0.01"' + 'min = "0"' + 'style="width: 85px"' + 'value="' + data.nextmps + '">';


                let rownextday = document.createElement('td');
                rownextday.innerHTML = '<input id="nextday' + index + '"' + 'type = "number"' + 'class = "form-control form-control-lg"' +
                    'step = "0.01"' + 'min = "0"' + 'style="width: 85px"' + 'value="' + data.nextday + '">';

                row.appendChild(rowdelete);
                row.appendChild(rownumber);
                row.appendChild(rowname);
                row.appendChild(rowformat);
                row.appendChild(rowunit);
                row.appendChild(rowlt);
                row.appendChild(rowamount);
                row.appendChild(rownowneed);
                row.appendChild(rownextneed);
                row.appendChild(rowsafestock);
                row.appendChild(rowclient);
                row.appendChild(rowmachine);
                row.appendChild(rowproduction);
                row.appendChild(rownowmps);
                row.appendChild(rownowday);
                row.appendChild(rownextmps);
                row.appendChild(rownextday);

                body.appendChild(row);
                tbl.appendChild(body);
                appenSVg(index);

                index = index + 1;
                count = count + 1;

                $("input").change(function () {
                    for (let i = 0; i < sessionStorage.getItem('consumecount'); i++) {
                        var nowmps = $("#nowmps" + i).val();
                        var amount = $("#amount" + i).val();
                        var nowday = $("#nowday" + i).val();
                        var nextmps = $("#nextmps" + i).val();
                        var nextday = $("#nextday" + i).val();
                        var lt = $("#lt" + i).text();
                        var nowneed = (nowmps * amount) / nowday;
                        var nextneed = (nextmps * amount) / nextday;
                        var safe = nextneed * lt;
                        nowneed = nowneed.toFixed(7);
                        nextneed = nextneed.toFixed(7);
                        safe = safe.toFixed(7);
                        $('#nowneed' + i).val(nowneed);
                        $('#nextneed' + i).val(nextneed);
                        $('#safestock' + i).val(safe);
                    }
                });
            },
            error: function (err) {
                //料號長度不為12
                if (err.status == 421) {
                    document.getElementById("numbererror").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('number').value = '';
                    document.getElementById("numbererror1").style.display = "none";

                }
                //料號不存在
                else if (err.status == 420) {
                    document.getElementById("numbererror1").style.display = "block";
                    document.getElementById('number').classList.add("is-invalid");
                    document.getElementById('number').value = '';
                    document.getElementById("numbererror").style.display = "none";
                }
            },
        });
    });

    $('#consumeadd').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        if (count == 0) {
            alert('no data');
            return false;
        }

        console.log(sessionStorage.getItem('consumecount'));
        var client = [];
        var machine = [];
        var production = [];
        var number = [];
        var consume = [];
        var row = [];
        var jobnumber = $("#jobnumber").val();
        var email = $("#email").val();

        for (let i = 0; i < sessionStorage.getItem('consumecount'); i++) {
            if ($("#client" + i).text() !== null && $("#client" + i).text() !== '') {
                client.push($("#client" + i).text());
                machine.push($("#machine" + i).text());
                production.push($("#production" + i).text());
                number.push($("#number" + i).text());
                consume.push($("#amount" + i).val());
                row.push(i);
            }
        }

        console.log(row);

        $.ajax({
            type: 'POST',
            url: "consumenewsubmit",
            data: {
                client: client,
                machine: machine,
                production: production,
                number: number,
                consume: consume,
                jobnumber: jobnumber,
                email: email,
                count: count,
                row : row,
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

                var mess = Lang.get('monthlyPRpageLang.total') + ' : ' + count + Lang.get('monthlyPRpageLang.record') +
                    Lang.get('monthlyPRpageLang.data') + ' ， ' + Lang.get('monthlyPRpageLang.success') + Lang.get('monthlyPRpageLang.new') +
                    ' : ' + data.record + Lang.get('monthlyPRpageLang.record') + Lang.get('monthlyPRpageLang.consume');
                alert(mess);

                var mess2 = Lang.get('monthlyPRpageLang.yellowrepeat');

                alert(mess2);

                for(let i = 0 ; i < row.length ; i++)
                {
                    console.log(typeof(data.check[i]));
                    console.log(typeof(row[i]));
                    let j = row.indexOf(parseInt((data.check)[i]));
                    console.log(j);
                    if(j != -1)
                    {
                        $('#row' + row[i]).remove();
                        count = count -1;
                    }
                    else
                    {
                        document.getElementById("row" + row[i]).style.backgroundColor = "yellow";
                    }
                }

                // $("#consumehead").hide();
                // $("#consumebody").hide();
                // $("#consumeupload").hide();

                // $('#url').append(' URL : ' + '<a>http://127.0.0.1/month/testconsume?' + data.database + '</a>');

            },
            error: function (err) {
                //transaction error
                if (err.status == 421) {
                    alert(err.responseJSON.message);
                    window.location.reload();
                }
            },
        });
    });

});
