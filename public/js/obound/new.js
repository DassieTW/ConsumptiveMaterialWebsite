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
            message: Lang.get("oboundpageLang.delete") + Lang.get("oboundpageLang.success"),
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
    $('#newmaterial').on('submit', function (e) {
        e.preventDefault();
        var number = $("#number").val();
        var name = $("#name").val();
        var format = $("#format").val();
        $.ajax({
            type: 'POST',
            url: "new",
            data: {
                number: number,
                name: name,
                format: format
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
                sessionStorage.setItem('omaterialcount', index + 1);
                document.getElementById('numbererror').style.display = "none";

                notyf.success({
                    message: Lang.get("oboundpageLang.add") + Lang.get("oboundpageLang.success"),
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
                    'style="width: 150px"' + 'value = "' + data.number + '"' + '" required>';

                let rowname = document.createElement('td');
                rowname.innerHTML = '<input id="name' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' + 'style="width: 150px"' +
                    'value = "' + data.name + '"' + '" required >';

                let rowformat = document.createElement('td');
                rowformat.innerHTML = '<input id="format' + index + '"' + 'type = "text"' + 'class = "form-control form-control-lg"' +
                    'value = "' + data.format + '"' + 'style="width: 150px"' + '" required >';


                row.appendChild(rowdelete);
                row.appendChild(rownumber);
                row.appendChild(rowname);
                row.appendChild(rowformat);

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
                }
            }
        });
    });

    $('#materialadd').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        if (count == 0) {
            notyf.open({
                type: 'warning',
                message: Lang.get('basicInfoLang.nodata'),
                duration: 3000,   //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
        }

        console.log(sessionStorage.getItem('omaterialcount'));

        var data = [];
        var number = [];
        var name = [];
        var format = [];
        var row = [];
        for (let i = 0; i < sessionStorage.getItem('omaterialcount'); i++) {
            if ($("#number" + i).val() !== null && $("#number" + i).val() !== '' && $("#number" + i).val() !== undefined) {
                number.push($("#number" + i).val());
                name.push($("#name" + i).val());
                format.push($("#format" + i).val());
                row.push(i.toString());
            }
        }

        data.push(number);
        data.push(name);
        data.push(format);

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

                var mess = Lang.get('oboundpageLang.total') + ' : ' + count + ' ' + Lang.get('oboundpageLang.record') + ' ' +
                    Lang.get('oboundpageLang.matsdata') + ' ， ' + Lang.get('oboundpageLang.success') + Lang.get('oboundpageLang.new') +
                    ' : ' + data.record + ' ' + Lang.get('oboundpageLang.record') + ' ' + Lang.get('oboundpageLang.matsdata');

                alert(mess);

                var mess2 = Lang.get('oboundpageLang.yellowrepeat');

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
                //transaction error
                if (err.status == 423) {
                    window.alert(err.responseJSON.message);
                    console.log(err.responseJSON.message);
                } // else if
            },
        });
    });
});
