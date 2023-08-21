$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(function () {
  $(".form-check-input").on("change", function () {
    var check = $("input[name=numberradio]:checked").val();
    if (check == 1) {
      // $("#number").prop('required', true);
      $("#numberarea").prop("required", false);
    } else {
      $("#number").prop("required", false);
      $("#numberarea").prop("required", true);
    }
  });

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

    var radio = $("input[name=numberradio]:checked", "#form1").val();
    var input;
    var send = $("#send").val();

    if (radio == 1) {
      input = $("#number").val();
    } // if
    else {
      input = $("#numberarea").val().split(/\r?\n/);
    } // else

    sessionStorage.setItem("lookInType", JSON.stringify(radio)); // for later vue to post request
    sessionStorage.setItem("lookInTargets", JSON.stringify(input)); // for later vue to post request
    sessionStorage.setItem("lookInSend", JSON.stringify(send)); // for later vue to post request

    window.location.href = "materialsearch";
  });
});
