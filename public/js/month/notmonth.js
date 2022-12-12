sessionStorage.clear();

$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  $("#notmonth").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").hide();

    var client = $("#client").val();
    var number = $("#number").val();
    var submit = buttonIndex;
    console.log(client);
    console.log(number);
    if (submit == "1") {
      if (client == null) {
        $("#client").addClass("is-invalid");
        document.getElementById("clienterror").style.display = "block";
        document.getElementById("client").focus();
        return false;
      } else if (number == "") {
        $("#number").addClass("is-invalid");
        document.getElementById("numbererror").style.display = "block";
        document.getElementById("number").focus();
        return false;
      } else if (number.length !== 12) {
        $("#number").addClass("is-invalid");
        document.getElementById("numbererror1").style.display = "block";
        document.getElementById("number").focus();
        return false;
      }
      $.ajax({
        type: "POST",
        url: "notmonthadd",
        data: {
          client: client,
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
          $("#var1").val(data.client);
          $("#var2").val(data.number);
          $("#var3").val(data.name);
          $("#var4").val(data.unit);
          $("#var5").val(data.month);
          $("#notmonthaddform").submit();
        },
        error: function (err) {
          console.log(err);
          //無料號
          if (err.status == 421) {
            document.getElementById("numbererror2").style.display = "block";
            document.getElementById("number").classList.add("is-invalid");
            document.getElementById("number").value = "";
            document.getElementById("numbererror").style.display = "none";
            document.getElementById("numbererror1").style.display = "none";
          }
          //transaction error
          else {
            alert(err.responseJSON.message);
            window.location.reload();
          }
        },
      });
    } else {
      if (number === "") number = null;
      sessionStorage.setItem("notmonthclient", JSON.stringify(client)); // for later vue to post request
      sessionStorage.setItem("notmonthisn", JSON.stringify(number)); // for later vue to post request
      window.location.href = "notmonthsearchok";
    }
  });
});
