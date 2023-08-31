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

    var client = $("#client").val();
    var isn = $("#number").val();
    var production = $("#production").val();
    var send = $("#send").val();
    var check = $("#date").is(":checked");
    var begin = $("#begin").val();
    var end = $("#end").val();
    if (isn === "") isn = null;

    sessionStorage.setItem("pickrecordclient", JSON.stringify(client)); // for later vue to post request
    sessionStorage.setItem("pickrecordisn", JSON.stringify(isn)); // for later vue to post request
    sessionStorage.setItem("pickrecordproduction", JSON.stringify(production)); // for later vue to post request
    sessionStorage.setItem("pickrecordsend", JSON.stringify(send)); // for later vue to post request
    sessionStorage.setItem("pickrecordcheck", JSON.stringify(check)); // for later vue to post request
    sessionStorage.setItem("pickrecordbegin", JSON.stringify(begin)); // for later vue to post request
    sessionStorage.setItem("pickrecordend", JSON.stringify(end)); // for later vue to post request

    window.location.href = "pickrecordsearch";
  });
});
