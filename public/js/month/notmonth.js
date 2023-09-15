sessionStorage.clear();

$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});
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
        Lang.get("monthlyPRpageLang.delete") +
        Lang.get("monthlyPRpageLang.success"),
      duration: 3000, //miliseconds, use 0 for infinite duration
      ripple: true,
      dismissible: true,
      position: {
        x: "right",
        y: "bottom",
      },
    });
    $(this).parent().parent().remove();
  }); // on delete btn click
} // appenSVg

$(function () {
  $("#notmonth").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").hide();

    var select = $(document.activeElement).val();
    if (select === "查詢" || select === "Search" || select === "查询") {
      window.location.href = "notmonthsearchok";
    } else {
      if (sessionStorage.getItem("notmonthcount") === null) {
        var index = 0;
      } else {
        var index = parseInt(sessionStorage.getItem("notmonthcount"));
      }

      var number = $("#number").val();
      var amount = $("#amount").val();

      if (number === "") {
        document.getElementById("numbererror").style.display = "block";
        document.getElementById("number").classList.add("is-invalid");
        document.getElementById("number").focus();
        return false;
      } else if (number.length !== 12) {
        document.getElementById("numbererror1").style.display = "block";
        document.getElementById("number").classList.add("is-invalid");
        document.getElementById("number").focus();
        return false;
      }
    }
    $.ajax({
      type: "POST",
      url: "notmonthadd",
      data: {
        number: number,
        amount: amount,
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
        sessionStorage.setItem("notmonthcount", index + 1);

        notyf.success({
          message:
            Lang.get("monthlyPRpageLang.add") +
            Lang.get("monthlyPRpageLang.success"),
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });

        document.getElementById("notmonthadd").style.display = "block";
        var tbl = document.getElementById("notmonthaddtable");
        var body = document.getElementById("notmonthaddbody");
        var row = document.createElement("tr");

        row.setAttribute("id", "row" + index);

        let rowdelete = document.createElement("td");
        rowdelete.innerHTML = "<a id=" + "deleteBtn" + index + "></a>";

        let rownumber = document.createElement("td");
        rownumber.innerHTML =
          "<span id=" + "number" + index + ">" + data.number + "</span>";

        let rowname = document.createElement("td");
        rowname.innerHTML =
          "<span id=" + "name" + index + ">" + data.name + "</span>";

        let rowamount = document.createElement("td");
        rowamount.innerHTML =
          '<input id="amount' +
          index +
          '"' +
          'type = "number"' +
          'class = "form-control form-control-lg"' +
          'min = "1"' +
          'value = "' +
          data.amount +
          '"' +
          'step = "1"' +
          'style="width: 100px"' +
          '">';
        let rowdesc = document.createElement("td");
        rowdesc.innerHTML =
          '<input id="desc' +
          index +
          '"' +
          'type = "text"' +
          'class = "form-control form-control-lg"' +
          'style="width: 100px"' +
          '">';

        row.appendChild(rowdelete);
        row.appendChild(rownumber);
        row.appendChild(rowname);
        row.appendChild(rowamount);
        row.appendChild(rowdesc);

        body.appendChild(row);
        tbl.appendChild(body);
        appenSVg(index);
      },
      error: function (err) {
        //料號不存在
        if (err.status === 421) {
          document.getElementById("numbererror2").style.display = "block";
          document.getElementById("number").classList.add("is-invalid");
          document.getElementById("number").value = "";
          document.getElementById("number").focus();
        } else {
          alert(err.responseJSON.message);
          return false;
        }
      },
    });
  });
  $("#notmonthadd").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").hide();

    var number = [];
    var desc = [];
    var amount = [];
    var row = [];

    for (let i = 0; i < sessionStorage.getItem("notmonthcount"); i++) {
      if ($("#number" + i).text() !== null && $("#number" + i).text() !== "") {
        number.push($("#number" + i).text());
        amount.push($("#amount" + i).val());
        desc.push($("#desc" + i).val());
        row.push(i.toString());
      }
    }
    if (number.length === 0) {
      notyf.open({
        type: "warning",
        message: Lang.get("monthlyPRpageLang.nodata"),
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
    count = parseInt(number.length);

    $.ajax({
      type: "POST",
      url: "notmonthsubmit",
      data: {
        amount: amount,
        number: number,
        desc: desc,
        count: count,
        row: row,
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
          Lang.get("monthlyPRpageLang.total") +
          " : " +
          data.record +
          " " +
          Lang.get("monthlyPRpageLang.record") +
          Lang.get("monthlyPRpageLang.notmonth") +
          Lang.get("monthlyPRpageLang.add") +
          Lang.get("monthlyPRpageLang.success");

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

        for (let i = 0; i < row.length; i++) {
          var same = row.filter(function (v) {
            return data.check.indexOf(v) > -1;
          });
        }
        for (let i = 0; i < same.length; i++) {
          console.log(same[i]);
          $("#row" + same[i]).remove();
          count = count - 1;
        }
      },

      error: function (err) {
        console.log(err);
        //transaction error

        alert(err.responseJSON.message);
        window.location.reload();
      },
    });
  });
});
