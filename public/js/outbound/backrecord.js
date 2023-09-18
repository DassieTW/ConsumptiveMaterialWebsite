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

    var isn = $("#number").val();
    var line = $("#line").val();
    var check = $("#date").is(":checked");
    var begin = $("#begin").val();
    var end = $("#end").val();
    if (isn === "") isn = null;

    sessionStorage.setItem("backrecordline", JSON.stringify(line)); // for later vue to post request
    sessionStorage.setItem("backrecordisn", JSON.stringify(isn)); // for later vue to post request
    sessionStorage.setItem("backrecordcheck", JSON.stringify(check)); // for later vue to post request
    sessionStorage.setItem("backrecordbegin", JSON.stringify(begin)); // for later vue to post request
    sessionStorage.setItem("backrecordend", JSON.stringify(end)); // for later vue to post request

    window.location.href = "backrecordsearch";
  });
});
