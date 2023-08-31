/*//show select 退料單號
$("#list").on("change", function () {
    var value = $("#list").val();
    $("#test").find("tr").not("#require").hide();
    var result_style = document.getElementById(value).style;
    result_style.display = "table-row";
    //document.getElementById("test").style.display = "block";
});*/

sessionStorage.clear();
$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});
var index = 0;
var count = $("#count").val();
count = parseInt(count);

function appenSVg(count) {
  // $(".amount").hover(function () {
  //     $(this).tooltip();
  // });

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
  $("#deleteBtn" + count).append(svg);
  var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
  var path1 = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path.setAttribute(
    "d",
    "M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"
  );
  path1.setAttribute(
    "d",
    "M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"
  );
  svg.setAttribute("width", "20");
  svg.setAttribute("height", "20");
  svg.setAttribute("fill", "#467fd0");
  svg.setAttribute("class", "bi bi-plus-square");
  svg.setAttribute("viewBox", "0 0 16 16");
  svg.appendChild(path);
  svg.appendChild(path1);
  $("#addBtn" + count).append(svg);
  count = count + 1;
} // appenSVg

function copyoption(index, count) {
  var select1 = document.getElementById("bound" + index);
  var select2 = document.getElementById("bound" + count);
  var status1 = document.getElementById("status" + index);
  var status2 = document.getElementById("status" + count);
  select2.innerHTML = select1.innerHTML;
  status2.innerHTML = status1.innerHTML;
  //$('#position'+ index + 'option').clone().appendTo('#position' + count);
}

function deleteBtn(index) {
  notyf.success({
    message:
      Lang.get("oboundpageLang.delete") + Lang.get("oboundpageLang.success"),
    duration: 3000, //miliseconds, use 0 for infinite duration
    ripple: true,
    dismissible: true,
    position: {
      x: "right",
      y: "bottom",
    },
  });
  count = count - 1;
  $("#deleteBtn" + index)
    .parent()
    .parent()
    .remove();
  // on delete btn click
}

function addBtn(index) {
  notyf.success({
    message:
      Lang.get("oboundpageLang.add") + Lang.get("oboundpageLang.success"),
    duration: 3000, //miliseconds, use 0 for infinite duration
    ripple: true,
    dismissible: true,
    position: {
      x: "right",
      y: "bottom",
    },
  });
  var tbl = document.getElementById("backlisttable");
  var body = document.getElementById("backlistbody");
  var row = document.createElement("tr");
  row.className = "isnRows";
  let rowdelete = document.createElement("td");
  rowdelete.innerHTML =
    "<a id=" +
    "deleteBtn" +
    count +
    " href=javascript:deleteBtn(" +
    count +
    ")></a>";

  let rowadd = document.createElement("td");
  rowadd.innerHTML =
    "<a id=" + "addBtn" + count + " href=javascript:addBtn(" + count + ")></a>";

  let rowclient = document.createElement("td");
  rowclient.innerHTML =
    "<span id=" +
    "client" +
    count +
    ">" +
    $("#client" + index).text() +
    "</span>";

  let rowmachine = document.createElement("td");
  rowmachine.innerHTML =
    "<span id=" +
    "machine" +
    count +
    ">" +
    $("#machine" + index).text() +
    "</span>";

  let rowproduction = document.createElement("td");
  rowproduction.innerHTML =
    "<span id=" +
    "production" +
    count +
    ">" +
    $("#production" + index).text() +
    "</span>";

  let rowbackreason = document.createElement("td");
  rowbackreason.innerHTML =
    "<span id=" +
    "backreason" +
    count +
    ">" +
    $("#backreason" + index).text() +
    "</span>";

  let rowline = document.createElement("td");
  rowline.innerHTML =
    "<span id=" + "line" + count + ">" + $("#line" + index).text() + "</span>";

  let rownumber = document.createElement("td");
  rownumber.innerHTML =
    "<span id=" +
    "number" +
    count +
    ">" +
    $("#number" + index).text() +
    "</span>";

  let rowname = document.createElement("td");
  rowname.innerHTML =
    "<span id=" + "name" + count + ">" + $("#name" + index).text() + "</span>";

  let rowformat = document.createElement("td");
  rowformat.innerHTML =
    "<span id=" +
    "format" +
    count +
    ">" +
    $("#format" + index).text() +
    "</span>";

  let rowadvance = document.createElement("td");
  rowadvance.innerHTML =
    "<span id=" +
    "advance" +
    count +
    ">" +
    $("#advance" + index).text() +
    "</span>";

  let rowamount = document.createElement("td");

  rowamount.innerHTML =
    '<div class="tooltip1"><input id="amount' +
    count +
    '"' +
    'type = "number"' +
    'class = "form-control amount"' +
    'min = "1"' +
    'value = "' +
    $("#amount" + index).val() +
    '"style="width: 100px"' +
    '"><span class="tooltip1text tooltip1-top"> ' +
    $("#amounttitle" + index).val() +
    "</span></div>";

  let rowremark = document.createElement("td");
  rowremark.innerHTML =
    "<span id=" +
    "remark" +
    count +
    ">" +
    $("#remark" + index).text() +
    "</span>";

  let rowreason = document.createElement("td");
  rowreason.innerHTML =
    '<input id="reason' +
    count +
    '"' +
    'type = "text"' +
    'class = "form-control"' +
    'style="width: 100px"' +
    ">";

  let rowlist = document.createElement("td");
  rowlist.innerHTML =
    "<span id=" + "list" + count + ">" + $("#list" + index).text() + "</span>";

  let rowopentime = document.createElement("td");
  rowopentime.innerHTML =
    "<span id=" +
    "opentime" +
    count +
    ">" +
    $("#opentime" + index).text() +
    "</span>";

  let rowstatus = document.createElement("td");
  rowstatus.innerHTML =
    '<select id="status' +
    count +
    '"' +
    'class = "form-select form-select-lg"' +
    'style="width: 150px"' +
    "required>";

  let rowbound = document.createElement("td");
  rowbound.innerHTML =
    '<select id="bound' +
    count +
    '"' +
    'class = "form-select form-select-lg"' +
    'style="width: 150px"' +
    "required>";

  row.appendChild(rowdelete);
  row.appendChild(rowadd);
  row.appendChild(rowclient);
  row.appendChild(rowmachine);
  row.appendChild(rowproduction);
  row.appendChild(rowbackreason);
  row.appendChild(rowline);
  row.appendChild(rownumber);
  row.appendChild(rowname);
  row.appendChild(rowformat);
  row.appendChild(rowadvance);
  row.appendChild(rowamount);
  row.appendChild(rowremark);
  row.appendChild(rowreason);
  row.appendChild(rowlist);
  row.appendChild(rowopentime);
  row.appendChild(rowstatus);
  row.appendChild(rowbound);
  body.appendChild(row);
  tbl.appendChild(body);
  copyoption(index, count);
  appenSVg(count);
  count = count + 1;
}

$(".receivelist").on("mouseover", function (e) {
  e.preventDefault();
  var ename = $(this).text();
  $("#pickpeople").val(ename);
});
$(".receivelist").on("click", function (e) {
  e.preventDefault();
  var ename = $(this).text();
  $("#pickpeople").val(ename);
});

$(".backlist").on("mouseover", function (e) {
  e.preventDefault();
  var ename = $(this).text();
  $("#backpeople").val(ename);
});
$(".backlist").on("click", function (e) {
  e.preventDefault();
  var ename = $(this).text();
  $("#backpeople").val(ename);
});

function myFunction() {
  var input, filter, ul, li, a, i;
  ul = document.getElementById("receivemenu");
  li = ul.getElementsByTagName("a");
  input = document.getElementById("pickpeople");
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

function myFunction2() {
  var input, filter, ul, li, a, i;
  ul = document.getElementById("backmenu");
  li = ul.getElementsByTagName("a");
  input = document.getElementById("backpeople");
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

$("#pickpeople").on("focus", function () {
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#receivemenu").show();
});
$("#pickpeople").on("input", function () {
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#receivemenu").show();
  myFunction();
});
$("#pickpeople").on("blur", function () {
  $("#receivemenu").hide();
});
$("#backpeople").on("focus", function () {
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#backmenu").show();
});
$("#backpeople").on("input", function () {
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#backmenu").show();
  myFunction2();
});
$("#backpeople").on("blur", function () {
  $("#backmenu").hide();
});

$(function () {
  // $(".amount").hover(function () {
  //     $(this).tooltip();
  // });

  $("#pickpeople").on("input", function () {
    $(window).on("keydown", function (event) {
      if (event.code === "Enter") {
        event.preventDefault();
        return false;
      }
    });
    var rfidpick = $("#pickpeople").val();
    rfidpick = rfidpick.slice(-9);
    $("#pickpeople").val(rfidpick);
  });
  $("#backpeople").on("input", function () {
    $(window).on("keydown", function (event) {
      if (event.code === "Enter") {
        event.preventDefault();
        return false;
      }
    });
    var rfidback = $("#backpeople").val();
    rfidback = rfidback.slice(-9);
    $("#backpeople").val(rfidback);
  });

  $("#backlist").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

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

    var data = [];
    var client = [];
    var machine = [];
    var production = [];
    var line = [];
    var backreason = [];
    var reason = [];
    var number = [];
    var amount = [];
    var list = [];
    var advance = [];
    var bound = [];
    var status = [];
    var name = [];
    var format = [];
    var opentime = [];
    var remark = [];

    var pick = $("#pickpeople").val();
    var back = $("#backpeople").val();

    var checkpeople = [];

    for (let i = 0; i < $("#checkcount").val(); i++) {
      checkpeople.push($("#checkpeople" + i).val());
    }

    for (let i = 0; i < count; i++) {
      if ($("#client" + i).text() !== null && $("#client" + i).text() !== "") {
        client.push($("#client" + i).text());
        machine.push($("#machine" + i).text());
        production.push($("#production" + i).text());
        line.push($("#line" + i).text());
        backreason.push($("#backreason" + i).text());
        reason.push($("#reason" + i).val());
        number.push($("#number" + i).text());
        advance.push(parseInt($("#advance" + i).text()));
        amount.push(parseInt($("#amount" + i).val()));
        list.push($("#list" + i).text());
        status.push($("#status" + i).val());
        bound.push($("#bound" + i).val());
        name.push($("#name" + i).text());
        format.push($("#format" + i).text());
        opentime.push($("#opentime" + i).text());
        remark.push($("#remark" + i).text());
      }
    }

    pick = pick.split(" ");
    var pickpeople = pick[0];
    back = back.split(" ");
    var backpeople = back[0];
    var check1 = checkpeople.indexOf(pickpeople);
    var check2 = checkpeople.indexOf(backpeople);

    if (check1 === -1) {
      notyf.open({
        type: "warning",
        message: Lang.get("oboundpageLang.noreceivepeople"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
          x: "right",
          y: "bottom",
        },
      });
      $("#pickpeople").addClass("is-invalid");
      return false;
    }

    if (check2 === -1) {
      notyf.open({
        type: "warning",
        message: Lang.get("oboundpageLang.nobackpeople"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
          x: "right",
          y: "bottom",
        },
      });
      $("#backpeople").addClass("is-invalid");
      return false;
    }

    for (let i = 0; i < count; i++) {
      if (amount[i] != advance[i] && reason[i] == "") {
        row = i + 1;
        $("#reasonerrrow")
          .empty()
          .append(Lang.get("oboundpageLang.row") + " " + row);
        document.getElementById("reasonerror").style.display = "block";
        document.getElementById("reason" + i).classList.add("is-invalid");
        return false;
      } else {
        continue;
      }
    }

    for (let i = 0; i < count; i++) {
      if (status[i] === "good product" || status === "良品") {
        status[i] = "良品";
      } else {
        status[i] = "不良品";
      }
    }
    data.push(client);
    data.push(machine);
    data.push(production);
    data.push(backreason);
    data.push(line);
    data.push(number);
    data.push(name);
    data.push(format);
    data.push(advance);
    data.push(amount);
    data.push(remark);
    data.push(reason);
    data.push(list);
    data.push(opentime);
    data.push(bound);
    data.push(status);

    $.ajax({
      type: "POST",
      url: "backlistsubmit",
      data: {
        backpeople: backpeople,
        pickpeople: pickpeople,
        AllData: JSON.stringify(data),
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
        var mess =
          Lang.get("oboundpageLang.total") +
          " : " +
          data.record +
          " " +
          Lang.get("oboundpageLang.record") +
          " " +
          Lang.get("oboundpageLang.outbackok") +
          " : " +
          data.list;
        notyf.open({
          type: "success",
          message: mess,
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });

        setTimeout(() => (window.location.href = "backlist"), 1500);
      },
      error: function (err) {
        //transaction error
        if (err.status === 421) {
          var mess = err.responseJSON.message;
          alert(mess);
          window.location.reload();
        }
      },
    });
  });
});
