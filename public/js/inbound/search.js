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
    var list = $("#innumber").val();
    var check = $("#date").is(":checked");
    var begin = $("#begin").val();
    var end = $("#end").val();
    var success = true;
    if (list !== "" && list.length !== 12) {
      document.getElementById("innumbererror").style.display = "block";
      document.getElementById("innumber").classList.add("is-invalid");
      document.getElementById("innumber").value = "";
      success = false;
    } else {
      document.getElementById("innumbererror").style.display = "none";
    }
    if (isn === "") isn = null;
    if (list === "") list = null;

    sessionStorage.setItem("inboundclient", JSON.stringify(client)); // for later vue to post request
    sessionStorage.setItem("inboundisn", JSON.stringify(isn)); // for later vue to post request
    sessionStorage.setItem("inboundlist", JSON.stringify(list)); // for later vue to post request
    sessionStorage.setItem("inboundcheck", JSON.stringify(check)); // for later vue to post request
    sessionStorage.setItem("inboundbegin", JSON.stringify(begin)); // for later vue to post request
    sessionStorage.setItem("inboundend", JSON.stringify(end)); // for later vue to post request

    if (success) {
      window.location.href = "inquire";
    } else {
      return false;
    }
  });
});
