sessionStorage.clear();

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});
$("#backreason").on("change", function () {
    var value = $("#backreason").val();
    if (value === "其他" || value === "other") {
        document.getElementById("inputreason").style.display = "block";
    } else {
        document.getElementById("inputreason").style.display = "none";
    }
});

var index = 0;
var count = 0;

function appenSVg(index) {
    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
    path.setAttribute(
        "d",
        "M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"
    );
    svg.setAttribute("width", "30");
    svg.setAttribute("height", "30");
    svg.setAttribute("fill", "#c94466");
    svg.setAttribute("class", "bi bi-x-circle-fill");
    svg.setAttribute("viewBox", "0 0 20 20");
    svg.appendChild(path);
    $("#deleteBtn" + index).append(svg);
    $("#deleteBtn" + index).on("click", function (e) {
        e.preventDefault();
        notyf.success({
            message:
                Lang.get("outboundpageLang.delete") +
                Lang.get("outboundpageLang.success"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
                x: "right",
                y: "bottom",
            },
        });
        count = count - 1;
        $(this).parent().parent().remove();
    }); // on delete btn click
} // appenSVg

$(function () {
    $("#back").on("submit", function (e) {
        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").hide();

        var line = $("#line").val();
        var backreason = $("#backreason").val();
        var number = $("#number").val();

        if (line === null) {
            document.getElementById("lineerror").style.display = "block";
            document.getElementById("line").classList.add("is-invalid");
            document.getElementById("line").focus();
            return false;
        } else if (backreason === null) {
            document.getElementById("backreasonerror").style.display = "block";
            document.getElementById("backreason").classList.add("is-invalid");
            document.getElementById("backreason").focus();
            return false;
        } else if (number === "") {
            document.getElementById("numbererror1").style.display = "block";
            document.getElementById("number").classList.add("is-invalid");
            document.getElementById("number").focus();
            return false;
        }
        if (backreason === "其他" || backreason === "other") {
            if ($("#reason").val() !== "") {
                backreason = $("#reason").val();
            } else {
                document.getElementById("inputreasonerror").style.display =
                    "block";
                document.getElementById("reason").classList.add("is-invalid");
                document.getElementById("reason").focus();
                return false;
            } // if else
        } // if

        // if the same line, reason, and number are already added, count up the input value with id "#amount + index"
        for (let i = 0; i < index + 1; i++) {
            if (
                $("#line" + i).text() === line &&
                $("#backreason" + i).text() === backreason &&
                $("#number" + i).text() === number
            ) {
                let amount = parseInt($("#amount" + i).val());
                $("#amount" + i).val(amount + 1);
                return false;
            } // if
        } // for

        $.ajax({
            type: "POST",
            url: "backadd",
            data: {
                line: line,
                backreason: backreason,
                number: number,
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
                $("body").loadingModal("destroy");
            },
            success: function (data) {
                sessionStorage.setItem("backcount", index + 1);

                notyf.success({
                    message:
                        Lang.get("outboundpageLang.add") +
                        Lang.get("outboundpageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

                document.getElementById("backadd").style.display = "block";
                var tbl = document.getElementById("backaddtable");
                var body = document.getElementById("backaddbody");
                var row = document.createElement("tr");

                let rowdelete = document.createElement("td");
                rowdelete.innerHTML = "<a id=" + "deleteBtn" + index + "></a>";

                let rownumber = document.createElement("td");
                rownumber.innerHTML =
                    "<span id=" +
                    "number" +
                    index +
                    ">" +
                    data.number +
                    "</span>";

                let rowname = document.createElement("td");
                rowname.innerHTML =
                    "<span id=" + "name" + index + ">" + data.name + "</span>";

                let rowformat = document.createElement("td");
                rowformat.innerHTML =
                    "<span id=" +
                    "format" +
                    index +
                    ">" +
                    data.format +
                    "</span>";

                let rowunit = document.createElement("td");
                rowunit.innerHTML =
                    "<span id=" + "unit" + index + ">" + data.unit + "</span>";

                let rowsend = document.createElement("td");
                rowsend.innerHTML =
                    "<span id=" + "send" + index + ">" + data.send + "</span>";

                let rowamount = document.createElement("td");

                rowamount.innerHTML =
                    '<input id="amount' +
                    index +
                    '"' +
                    'type = "number"' +
                    'class = "form-control amount"' +
                    'min = "1"' +
                    'value = "1"' +
                    'style="width: 100px"' +
                    '">';

                let rowremark = document.createElement("td");
                rowremark.innerHTML =
                    '<input id="remark' +
                    index +
                    '"' +
                    'type = "text"' +
                    'class = "form-control"' +
                    'style="width: 100px"' +
                    ">";

                let rowline = document.createElement("td");
                rowline.innerHTML =
                    "<span id=" + "line" + index + ">" + data.line + "</span>";

                let rowbackreason = document.createElement("td");
                rowbackreason.innerHTML =
                    "<span id=" +
                    "backreason" +
                    index +
                    ">" +
                    data.backreason +
                    "</span>";

                row.appendChild(rowdelete);
                row.appendChild(rownumber);
                row.appendChild(rowname);
                row.appendChild(rowformat);
                row.appendChild(rowunit);
                row.appendChild(rowsend);
                row.appendChild(rowamount);
                row.appendChild(rowremark);
                row.appendChild(rowline);
                row.appendChild(rowbackreason);

                body.appendChild(row);
                tbl.appendChild(body);
                appenSVg(index);

                index = index + 1;
                count = count + 1;
            },
            error: function (err) {
                //料號不存在
                if (err.status === 420) {
                    document.getElementById("numbererror1").style.display =
                        "block";
                    document
                        .getElementById("number")
                        .classList.add("is-invalid");
                    document.getElementById("number").value = "";
                    document.getElementById("number").focus();
                } // if
            },
        });
    });
    $("#backadd").on("submit", function (e) {
        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").hide();

        if (count === 0) {
            notyf.open({
                type: "warning",
                message: Lang.get("basicInfoLang.nodata"),
                duration: 3000, //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom",
                },
            });
            return false;
        }

        console.log(sessionStorage.getItem("backcount"));
        var data = [];
        var line = [];
        var backreason = [];
        var number = [];
        var name = [];
        var format = [];
        var unit = [];
        var amount = [];
        var remark = [];

        for (let i = 0; i < sessionStorage.getItem("backcount"); i++) {
            if (
                $("#number" + i).text() !== null &&
                $("#number" + i).text() !== ""
            ) {
                line.push($("#line" + i).text());
                backreason.push($("#backreason" + i).text());
                number.push($("#number" + i).text());
                name.push($("#name" + i).text());
                format.push($("#format" + i).text());
                unit.push($("#unit" + i).text());
                amount.push($("#amount" + i).val());
                remark.push($("#remark" + i).val());
            }
        }

        data.push(line);
        data.push(backreason);
        data.push(number);
        data.push(name);
        data.push(format);
        data.push(unit);
        data.push(amount);
        data.push(remark);

        $.ajax({
            type: "POST",
            url: "backaddsubmit",
            data: {
                AllData: JSON.stringify(data),
                count: count,
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
                $("body").loadingModal("destroy");
            },
            success: function (data) {
                var mess =
                    Lang.get("outboundpageLang.total") +
                    ":" +
                    data.record +
                    Lang.get("outboundpageLang.record") +
                    Lang.get("outboundpageLang.add") +
                    Lang.get("outboundpageLang.success") +
                    " ， " +
                    Lang.get("outboundpageLang.backlistnum") +
                    " : " +
                    data.message;
                notyf.open({
                    type: "success",
                    message: mess,
                    duration: 0, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });

                setTimeout(() => window.location.reload(), 1500);
            },
            error: function (err) {
                //transaction error
                if (err.status === 420) {
                    alert(err.responseJSON.message);
                    window.location.reload();
                }
            },
        });
    });
});
