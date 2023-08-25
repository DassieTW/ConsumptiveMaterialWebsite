var count = $("#count").val();
for (var i = 0; i < count; i++) {
  var nowpeople = $("#data3" + i).val();
  var nowline = $("#data4" + i).val();
  var nowclass = $("#data5" + i).val();
  var nowuse = $("#data6" + i).val();
  var nowchange = $("#data7" + i).val();
  var nextpeople = $("#data9" + i).val();
  var nextline = $("#data10" + i).val();
  var nextclass = $("#data11" + i).val();
  var nextuse = $("#data12" + i).val();
  var nextchange = $("#data13" + i).val();
  var mpq = $("#data1" + i).val();
  var lt = $("#data2" + i).val();
  var now = (nowpeople * nowclass * nowline * nowuse * nowchange) / mpq;
  var next = (nextpeople * nextclass * nextline * nextuse * nextchange) / mpq;
  var safe = next * lt;
  now = now.toFixed(7);
  next = next.toFixed(7);
  safe = safe.toFixed(7);
  $("#data8" + i).val(now);
  $("#data14" + i).val(next);
  $("#data15" + i).val(safe);
}
$(function () {
  $("input").on("change", function () {
    for (var i = 0; i < count; i++) {
      var nowpeople = $("#data3" + i).val();
      var nowline = $("#data4" + i).val();
      var nowclass = $("#data5" + i).val();
      var nowuse = $("#data6" + i).val();
      var nowchange = $("#data7" + i).val();
      var nextpeople = $("#data9" + i).val();
      var nextline = $("#data10" + i).val();
      var nextclass = $("#data11" + i).val();
      var nextuse = $("#data12" + i).val();
      var nextchange = $("#data13" + i).val();
      var mpq = $("#data1" + i).val();
      var lt = $("#data2" + i).val();
      var now = (nowpeople * nowclass * nowline * nowuse * nowchange) / mpq;
      var next =
        (nextpeople * nextclass * nextline * nextuse * nextchange) / mpq;
      var safe = next * lt;
      now = now.toFixed(7);
      next = next.toFixed(7);
      safe = safe.toFixed(7);
      $("#data8" + i).val(now);
      $("#data14" + i).val(next);
      $("#data15" + i).val(safe);
    }
  });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $("#uploadstand").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var row = [];
    var client = [];
    var number = [];
    var production = [];
    var machine = [];
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
    var email = $("#email").val();
    email = email + $("#emailTail option:selected").text();

    var count = $("#count").val();

    for (let i = 0; i < count; i++) {
      if (
        $("#data16" + i).val() !== null &&
        $("#data16" + i).val() !== undefined &&
        $("#data16" + i).val() !== " "
      ) {
        client.push($("#data16" + i).val());
        machine.push($("#data17" + i).val());
        production.push($("#data18" + i).val());
        number.push($("#data0" + i).val());
        nowpeople.push($("#data3" + i).val());
        nowline.push($("#data4" + i).val());
        nowclass.push($("#data5" + i).val());
        nowuse.push($("#data6" + i).val());
        nowchange.push($("#data7" + i).val());
        nextpeople.push($("#data9" + i).val());
        nextline.push($("#data10" + i).val());
        nextclass.push($("#data11" + i).val());
        nextuse.push($("#data12" + i).val());
        nextchange.push($("#data13" + i).val());
        row.push(i.toString());
      } else {
        continue;
      }
    }

    $.ajax({
      type: "POST",
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
        email: email,
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
          count +
          Lang.get("monthlyPRpageLang.record") +
          Lang.get("monthlyPRpageLang.data") +
          " ， " +
          Lang.get("monthlyPRpageLang.success") +
          Lang.get("monthlyPRpageLang.new") +
          " : " +
          data.record +
          Lang.get("monthlyPRpageLang.record") +
          Lang.get("monthlyPRpageLang.stand");
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

        var mess2 = Lang.get("monthlyPRpageLang.yellowrepeat");

        setTimeout(
          () =>
            notyf.open({
              type: "info",
              message: mess2,
              duration: 3000, //miliseconds, use 0 for infinite duration
              ripple: true,
              dismissible: true,
              position: {
                x: "right",
                y: "bottom",
              },
            }),
          1000
        );
        for (let i = 0; i < row.length; i++) {
          var same = row.filter(function (v) {
            return data.check.indexOf(v) > -1;
          });
          var diff = row.filter(function (v) {
            return data.check.indexOf(v) == -1;
          });
        }
        for (let i = 0; i < same.length; i++) {
          $("#row" + same[i]).remove();
        }
        for (let i = 0; i < diff.length; i++) {
          document.getElementById("row" + diff[i]).style.backgroundColor =
            "yellow";
        }
        // $("#standhead").hide();
        // $("#standbody").hide();
        // $("#standupload").hide();

        // $('#url').append(' URL : ' + '<a>http://127.0.0.1/month/teststand?' + data.database + '</a>');
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
