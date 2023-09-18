//if 月使用量 disabled 儲位
$(".basic").on("change", function () {
  $(".basic").not(this).prop("checked", false);
  var checkedValue = $(".basic:checked").val();
  if (checkedValue === "1") {
    $("#position").attr("disabled", true);
  } else {
    $("#position").attr("disabled", false);
  }
});

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
    $(".invalid-feedback").hide();

    var isn = $("#number").val();
    var loc = $("#position").val();
    var send = $("#send").val();
    var month = $("#month").is(":checked");
    var nogood = $("#nogood").is(":checked");

    if (isn === "") isn = null;

    sessionStorage.setItem("inboundstockisn", JSON.stringify(isn)); // for later vue to post request
    sessionStorage.setItem("inboundstockloc", JSON.stringify(loc)); // for later vue to post request
    sessionStorage.setItem("inboundstocksend", JSON.stringify(send)); // for later vue to post request
    sessionStorage.setItem("inboundstockmonth", JSON.stringify(month)); // for later vue to post request
    sessionStorage.setItem("inboundstocknogood", JSON.stringify(nogood)); // for later vue to post request

    if (month === true) {
      window.location.href = "searchstocksubmit1";
    } else {
      window.location.href = "searchstocksubmit";
    }
  });
});
