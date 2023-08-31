$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $("#form1").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var bound = $("#bound").val();
    var client = $("#client").val();
    var number = $("#number").val();
    var check = $("#date").is(":checked");
    var begin = $("#begin").val();
    var end = $("#end").val();
    var success = true;

    if (number === "") number = null;

    sessionStorage.setItem("oboundbound", JSON.stringify(bound)); // for later vue to post request
    sessionStorage.setItem("oboundclient", JSON.stringify(client)); // for later vue to post request
    sessionStorage.setItem("oboundisn", JSON.stringify(number)); // for later vue to post request
    sessionStorage.setItem("oboundcheck", JSON.stringify(check)); // for later vue to post request
    sessionStorage.setItem("oboundbegin", JSON.stringify(begin)); // for later vue to post request
    sessionStorage.setItem("oboundend", JSON.stringify(end)); // for later vue to post request

    if (success) {
      window.location.href = "inboundsearchok";
    } else {
      return false;
    }
  });
});
