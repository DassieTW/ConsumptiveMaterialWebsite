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
    var sxb = $("#sxb").val();
    var send = $("#send").val();
    var begin = $("#begin").val();
    var end = $("#end").val();

    if (isn === "") isn = null;
    if (sxb === "") sxb = null;

    sessionStorage.setItem("sxbclient", JSON.stringify(client)); // for later vue to post request
    sessionStorage.setItem("sxbisn", JSON.stringify(isn)); // for later vue to post request
    sessionStorage.setItem("sxbsend", JSON.stringify(send)); // for later vue to post request
    sessionStorage.setItem("sxbsxb", JSON.stringify(sxb)); // for later vue to post request
    sessionStorage.setItem("sxbbegin", JSON.stringify(begin)); // for later vue to post request
    sessionStorage.setItem("sxbend", JSON.stringify(end)); // for later vue to post request

    window.location.href = "sxbsearch";
  });
});
