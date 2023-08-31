$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

function myFunction() {
  var input, filter, ul, li, a, i;
  ul = document.getElementById("outmenu");
  li = ul.getElementsByTagName("a");
  input = document.getElementById("outpeople");
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

$(".outlist").on("mouseover", function (e) {
  e.preventDefault();
  var ename = $(this).text();
  $("#outpeople").val(ename);
});
$(".outlist").on("click", function (e) {
  e.preventDefault();
  var ename = $(this).text();
  $("#outpeople").val(ename);
});

$("#outpeople").on("focus", function () {
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#outmenu").show();
});
$("#outpeople").on("input", function () {
  $(window).on("keydown", function (event) {
    if (event.code === "Enter") {
      event.preventDefault();
      return false;
    }
  });
  $("#outmenu").show();
  myFunction();
});
$("#outpeople").on("blur", function () {
  $("#outmenu").hide();
});

$(document).ready(function () {
  $("#outpeople").on("input", function () {
    $(window).on("keydown", function (event) {
      if (event.code === "Enter") {
        event.preventDefault();
        return false;
      }
    });
    var rfidpick = $("#outpeople").val();
    rfidpick = rfidpick.slice(-9);
    // $("#rfidpickpeople").val(rfidpick);
    $("#outpeople").val(rfidpick);
  });

  $("#outlist").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var checkpeople = [];

    for (let i = 0; i < $("#checkcount").val(); i++) {
      checkpeople.push($("#checkpeople" + i).val());
    }

    var count = $("#count").val();
    var clients = new Array();
    var nowstocks = new Array();
    var realamounts = new Array();
    var positions = new Array();
    console.log(count);
    for (var i = 0; i < count; i++) {
      clients[i] = $("#datab" + i).val();
      nowstocks[i] = $("#datag" + i).val();
      realamounts[i] = $("#datah" + i).val();
      positions[i] = $("#datai" + i).val();
    }
    var list = $("#dataa" + "0").val();
    var number = $("#datac" + "0").val();
    var name = $("#datad" + "0").val();
    var format = $("#datae" + "0").val();
    var preamount = $("#dataf" + "0").val();
    var outfactory = $("#dataj" + "0").val();
    var receivefac = $("#datak" + "0").val();

    var out = $("#outpeople").val();
    out = out.split(" ");
    var outpeople = out[0];

    var row = 0;
    var amount = 0;

    for (let i = 0; i < $("#checkcount").val(); i++) {
      checkpeople.push($("#checkpeople" + i).val());
    }

    var check1 = checkpeople.indexOf(outpeople);

    for (var i = 0; i < count; i++) {
      if (parseInt(realamounts[i]) > parseInt(nowstocks[i])) {
        row = i + 1;
        var mess =
          Lang.get("bupagelang.row") +
          " : " +
          row +
          " " +
          Lang.get("bupagelang.amounterr1");
        notyf.open({
          type: "warning",
          message: mess,
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
        return false;
      } else {
        continue;
      }
    }
    for (var i = 0; i < count; i++) {
      amount = amount + parseInt(realamounts[i]);
    }

    //check has people
    if (check1 === -1) {
      notyf.open({
        type: "warning",
        message: Lang.get("bupagelang.nooutpeople"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
          x: "right",
          y: "bottom",
        },
      });
      $("#outpeople").addClass("is-invalid");
      return false;
    }

    var mess =
      Lang.get("bupagelang.preamount") +
      " : " +
      preamount +
      " " +
      Lang.get("bupagelang.realamount") +
      " : " +
      amount +
      " " +
      Lang.get("bupagelang.dblist") +
      " : " +
      list +
      "\n" +
      Lang.get("bupagelang.checktrans");
    var sure = window.confirm(mess);

    if (sure !== true) {
      return false;
    } else {
      $.ajax({
        type: "POST",
        url: "outlistsubmit",
        data: {
          list: list,
          clients: clients,
          number: number,
          name: name,
          format: format,
          preamount: preamount,
          nowstocks: nowstocks,
          realamounts: realamounts,
          positions: positions,
          outfactory: outfactory,
          receivefac: receivefac,
          count: count,
          outpeople: outpeople,
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
            Lang.get("bupagelang.dblist") +
            " : " +
            data.message +
            " " +
            Lang.get("bupagelang.changeok");
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
          setTimeout(() => (window.location.href = "outlist"), 1500);
        },
        error: function (err) {
          //transaction error
          if (err.status === 420) {
            console.log(err.status);
            alert(err.responseJSON.message);
          }
        },
      });
    }
  });
});
