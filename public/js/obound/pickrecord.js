$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
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

    var client = $("#client").val();
    var isn = $("#number").val();
    var production = $("#production").val();
    var check = $("#date").is(":checked");
    var begin = $("#begin").val();
    var end = $("#end").val();
    if (isn === "") isn = null;

    sessionStorage.setItem("oboundpickrecordclient", JSON.stringify(client)); // for later vue to post request
    sessionStorage.setItem("oboundpickrecordisn", JSON.stringify(isn)); // for later vue to post request
    sessionStorage.setItem(
      "oboundpickrecordproduction",
      JSON.stringify(production)
    ); // for later vue to post request
    sessionStorage.setItem("oboundpickrecordcheck", JSON.stringify(check)); // for later vue to post request
    sessionStorage.setItem("oboundpickrecordbegin", JSON.stringify(begin)); // for later vue to post request
    sessionStorage.setItem("oboundpickrecordend", JSON.stringify(end)); // for later vue to post request

    window.location.href = "pickrecordsearch";
  });
});
