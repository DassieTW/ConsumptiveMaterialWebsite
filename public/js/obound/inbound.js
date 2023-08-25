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
    $(this).parent().parent().remove();
  }); // on delete btn click
} // appenSVg

function copyoption(count) {
  var select1 = document.getElementById("copybound");
  var select2 = document.getElementById("bound" + count);
  select2.innerHTML = select1.innerHTML;
  console.log(1);
  //$('#position'+ index + 'option').clone().appendTo('#position' + count);
}

$(".inboundlist").on("mouseover", function (e) {
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
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#inboundmenu").show();
});
$("#inpeople").on("input", function () {
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#inboundmenu").show();
  myFunction();
});
$("#inpeople").on("blur", function () {
  $("#inboundmenu").hide();
});

$(function () {
  $("#add").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").hide();

    var client = $("#client").val();
    var inreason = $("#inreason").val();
    var number = $("#number").val();

    if (client === null) {
      document.getElementById("clienterror").style.display = "block";
      document.getElementById("client").classList.add("is-invalid");
      document.getElementById("client").focus();
      return false;
    } else if (inreason === null) {
      document.getElementById("inreasonerror").style.display = "block";
      document.getElementById("inreason").classList.add("is-invalid");
      document.getElementById("inreason").focus();
      return false;
    } else if (number === "") {
      document.getElementById("numbererror").style.display = "block";
      document.getElementById("number").classList.add("is-invalid");
      document.getElementById("number").focus();
      return false;
    }

    $.ajax({
      type: "POST",
      url: "inboundnew",
      data: {
        client: client,
        inreason: inreason,
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
        document.getElementById("numbererror").style.display = "none";
        sessionStorage.setItem("inboundadd", index + 1);

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

        document.getElementById("inboundaddform").style.display = "block";
        var tbl = document.getElementById("inboundaddtable");
        var body = document.getElementById("inboundaddbody");
        var row = document.createElement("tr");

        let rowdelete = document.createElement("td");
        rowdelete.innerHTML = "<a id=" + "deleteBtn" + index + "></a>";

        let rowclient = document.createElement("td");
        rowclient.innerHTML =
          "<span id=" + "client" + index + ">" + data.client + "</span>";

        let rownumber = document.createElement("td");
        rownumber.innerHTML =
          "<span id=" + "number" + index + ">" + data.number + "</span>";

        let rowname = document.createElement("td");
        rowname.innerHTML =
          "<span id=" + "name" + index + ">" + data.name + "</span>";

        let rowformat = document.createElement("td");
        rowformat.innerHTML =
          "<span id=" + "format" + index + ">" + data.format + "</span>";

        let rowamount = document.createElement("td");
        rowamount.innerHTML =
          '<input id="amount' +
          index +
          '"' +
          'type = "number"' +
          'class = "form-control"' +
          'min = "1"' +
          'value = "1"' +
          'style="width: 100px"' +
          ">";

        let rowmark = document.createElement("td");
        rowmark.innerHTML =
          '<input id="mark' +
          index +
          '"' +
          'type = "text"' +
          'class = "form-control"' +
          'style="width: 100px"' +
          ">";

        let rowinreason = document.createElement("td");
        rowinreason.innerHTML =
          "<span id=" + "inreason" + index + ">" + data.inreason + "</span>";

        let rowbound = document.createElement("td");
        rowbound.innerHTML =
          '<select id="bound' +
          index +
          '"' +
          'class = "form-select form-select-lg"' +
          'style="width: 150px"' +
          ">";

        row.appendChild(rowdelete);
        row.appendChild(rowclient);
        row.appendChild(rownumber);
        row.appendChild(rowname);
        row.appendChild(rowformat);
        row.appendChild(rowamount);
        row.appendChild(rowmark);
        row.appendChild(rowinreason);
        row.appendChild(rowbound);

        body.appendChild(row);
        tbl.appendChild(body);
        appenSVg(index);
        copyoption(index);

        index = index + 1;
        count = count + 1;
      },
      error: function (err) {
        console.log(err);

        //無料號
        if (err.status == 421) {
          document.getElementById("numbererror").style.display = "block";
          document.getElementById("number").classList.add("is-invalid");
          document.getElementById("number").value = "";
          document.getElementById("number").focus();
        }
      },
    });
  });

  $("#inpeople").on("input", function () {
    $(window).on("keydown", function (event) {
      if (event.code === "Enter") {
        event.preventDefault();
        return false;
      }
    });
    var rfidpick = $("#inpeople").val();
    rfidpick = rfidpick.slice(-9);
    $("#inpeople").val(rfidpick);
  });

  $("#inboundaddform").on("submit", function (e) {
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

    var client = [];
    var number = [];
    var name = [];
    var format = [];
    var bound = [];
    var amount = [];
    var inreason = [];
    var mark = [];
    var row = [];
    var j = 0;

    for (let i = 0; i < sessionStorage.getItem("inboundadd"); i++) {
      if ($("#client" + i).text() !== null && $("#client" + i).text() !== "") {
        row[j] = i;
        client.push($("#client" + i).text());
        number.push($("#number" + i).text());

        if ($("#bound" + i).val() !== null) {
          bound.push($("#bound" + i).val());
        } else {
          notyf.open({
            type: "warning",
            message:
              Lang.get("oboundpageLang.row") +
              " " +
              (i + 1) +
              " " +
              Lang.get("oboundpageLang.enterbound"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });

          document.getElementById("bound" + i).classList.add("is-invalid");
          document.getElementById("bound" + i).focus();
          return false;
        }
        inreason.push($("#inreason" + i).text());
        name.push($("#name" + i).text());
        format.push($("#format" + i).text());
        amount.push(parseInt($("#amount" + i).val()));
        mark.push($("#mark" + i).val());
        j = j + 1;
      }
    }

    var inpeo = $("#inpeople").val();
    inpeo = inpeo.split(" ");
    var inpeople = inpeo[0];
    var checkpeople = [];

    for (let i = 0; i < $("#checkcount").val(); i++) {
      checkpeople.push($("#checkpeople" + i).val());
    }
    var check1 = checkpeople.indexOf(inpeople);

    if (check1 === -1) {
      notyf.open({
        type: "warning",
        message: Lang.get("oboundpageLang.noinpeople"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
          x: "right",
          y: "bottom",
        },
      });
      $("#inpeople").addClass("is-invalid");
      return false;
    }

    var data = [];
    data.push(client);
    data.push(number);
    data.push(bound);
    data.push(amount);
    data.push(inreason);
    data.push(name);
    data.push(format);
    data.push(mark);
    $.ajax({
      type: "POST",
      url: "inboundnewsubmit",
      data: {
        AllData: JSON.stringify(data),
        inpeople: inpeople,
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
          Lang.get("oboundpageLang.total") +
          " : " +
          data.record +
          " " +
          Lang.get("oboundpageLang.record") +
          " " +
          Lang.get("oboundpageLang.add") +
          " " +
          Lang.get("oboundpageLang.success") +
          " " +
          Lang.get("oboundpageLang.inlist") +
          " : " +
          data.message;
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

        setTimeout(() => window.location.reload(), 1500);
      },
      error: function (err) {
        //transaction error
        if (err.status === 421) {
          alert(err.responseJSON.message);
          window.location.reload();
        }
      },
    });
  });
});
