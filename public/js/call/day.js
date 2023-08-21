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
  function quickSearch() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = $("#numbersearch").val();
    //var isISN = $("#toggle-state").is(":checked");
    console.log(input); // test
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
  } // quickSearch function

  $("#numbersearch").on("input", function (e) {
    e.preventDefault();
    quickSearch();
  });

  var count = $("#count").val();
  for (var i = 0; i < count; i++) {
    var day = $("#staydaya" + i).html();

    if (day > 30 && day <= 60) {
      $("#staydaya" + i).css("background-color", "yellow");
    } else if (day > 60 && day <= 90) {
      $("#staydaya" + i).css("background-color", "orange");
    } else if (day > 90) {
      $("#staydaya" + i).css("background-color", "red");
    }
  }

  $("#day").on("submit", function (e) {
    e.preventDefault();

    var count = $("#count").val();

    var number = [];
    var client = [];
    var remark = [];

    for (let i = 0; i < count; i++) {
      if ($("#remark" + i).val() !== "") {
        client.push($("#client" + i).val());
        number.push($("#number" + i).val());
        remark.push($("#remark" + i).val());
      }
    }
    $.ajax({
      type: "POST",
      url: "dayremark",
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
