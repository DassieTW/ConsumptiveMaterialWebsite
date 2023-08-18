$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});
$(window).on("load", function () {
  // PAGE IS FULLY LOADED
  // FADE OUT YOUR OVERLAYING DIV
  $("body").loadingModal("hide");
  $("body").loadingModal("destroy");
});
$(function () {
  var oldmark = [];
  function quickSearch() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = $("#numbersearch").val();
    //var isISN = $("#toggle-state").is(":checked");
    // filter = input.value;
    // Loop through all table rows, and hide those who don't match the search query
    $(".isnRows").each(function (i, obj) {
      txtValue = $(this).find("input[id^='number']").val();
      // console.log("now checking text : " + txtValue); // test
      if (txtValue.indexOf(input) > -1) {
        obj.style.display = "";
      } else {
        obj.style.display = "none";
      } // if else
    });
    $(".isnRows1").each(function (i, obj) {
      txtValue = $(this).find("input[id^='numbera']").val();
      // console.log("now checking text : " + txtValue); // test
      if (txtValue.indexOf(input) > -1) {
        obj.style.display = "";
      } else {
        obj.style.display = "none";
      } // if else
    });
    $(".isnRows2").each(function (i, obj) {
      txtValue = $(this).find("input[id^='numberb']").val();
      // console.log("now checking text : " + txtValue); // test
      if (txtValue.indexOf(input) > -1) {
        obj.style.display = "";
      } else {
        obj.style.display = "none";
      } // if else
    });
  } // quickSearch function

  $("#numbersearch").on("input", function (e) {
    e.preventDefault();
    quickSearch();
  });

  var count = $("#count").val();
  for (var i = 0; i < count; i++) {
    var safe = $("#safea" + i).html();
    var stock = $("#stocka" + i).html();
    var percent = stock / safe;

    if (percent < 0.5) {
      $("#safea" + i).css("background-color", "red");
    } else if (percent >= 0.5 && percent < 0.8) {
      $("#safea" + i).css("background-color", "orange");
    } else if (percent >= 0.8) {
      $("#safea" + i).css("background-color", "yellow");
    } else {
      $("#safea" + i).css("background-color", "red");
    }
  }

  var count1 = $("#count1").val();
  for (var i = 0; i < count1; i++) {
    var safe = $("#safeb" + i).html();
    var stock = $("#stockb" + i).html();
    var percent = stock / safe;

    if (percent < 0.5) {
      $("#safeb" + i).css("background-color", "red");
    } else if (percent >= 0.5 && percent < 0.8) {
      $("#safeb" + i).css("background-color", "orange");
    } else if (percent >= 0.8) {
      $("#safeb" + i).css("background-color", "yellow");
    } else {
      $("#safeb" + i).css("background-color", "red");
    }
  }

  var count2 = $("#count2").val();
  for (var i = 0; i < count2; i++) {
    var safe = $("#safec" + i).html();
    var stock = $("#stockc" + i).html();
    var percent = stock / safe;
    if (percent < 0.5) {
      $("#safec" + i).css("background-color", "red");
    } else if (percent >= 0.5 && percent < 0.8) {
      $("#safec" + i).css("background-color", "orange");
    } else if (percent >= 0.8) {
      $("#safec" + i).css("background-color", "yellow");
    } else {
      $("#safec" + i).css("background-color", "red");
    }
  }
  for (let i = 0; i < count; i++) {
    oldmark.push($("#remark" + i).val());
  }
  for (let i = 0; i < count1; i++) {
    oldmark.push($("#remarka" + i).val());
  }
  for (let i = 0; i < count2; i++) {
    oldmark.push($("#remarkb" + i).val());
  }

  $("#safe").on("submit", function (e) {
    e.preventDefault();

    var number = [];
    var client = [];
    var remark = [];
    var realremark = [];
    for (let i = 0; i < count; i++) {
      //   if ($("#remark" + i).val() !== "") {
      client.push($("#client" + i).val());
      number.push($("#number" + i).val());
      remark.push($("#remark" + i).val());
      //   }
    }
    for (let i = 0; i < count1; i++) {
      //   if ($("#remarka" + i).val() !== "") {
      client.push($("#clienta" + i).val());
      number.push($("#numbera" + i).val());
      remark.push($("#remarka" + i).val());
      //   }
    }
    for (let i = 0; i < count2; i++) {
      //if ($("#remarkb" + i).val() !== "") {
      client.push("非月請購");
      number.push($("#numberb" + i).val());
      remark.push($("#remarkb" + i).val());
      //}
    }

    console.log(client);
    $.ajax({
      type: "POST",
      url: "saferemark",
      data: {
        client: client,
        number: number,
        remark: remark,
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
        console.log(data.boolean);
        var mess =
          Lang.get("callpageLang.saveremark") +
          " " +
          Lang.get("callpageLang.success");

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

        setTimeout(
          () =>
            $("body").loadingModal({
              text: "Loading...",
              animation: "circle",
            }),
          500
        );
        setTimeout(() => window.location.reload(), 1000);
      },
      error: function (jqXHR, textStatus, errorThrown) {
        console.warn(jqXHR.responseText);
        alert(errorThrown);
        window.location.reload();
      },
    });
  });
});
