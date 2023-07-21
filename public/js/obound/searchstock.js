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
    $(".invalid-feedback").hide();

    var client = $("#client").val();
    var isn = $("#number").val();
    var bound = $("#bound").val();
    var nogood = $("#nogood").is(":checked");

    if (isn === "") isn = null;

    sessionStorage.setItem("oboundstockclient", JSON.stringify(client)); // for later vue to post request
    sessionStorage.setItem("oboundstockisn", JSON.stringify(isn)); // for later vue to post request
    sessionStorage.setItem("oboundstockbound", JSON.stringify(bound)); // for later vue to post request
    sessionStorage.setItem("oboundstocknogood", JSON.stringify(nogood)); // for later vue to post request

    window.location.href = "searchstocksubmit";
  });
});
