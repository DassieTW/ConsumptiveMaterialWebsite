$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  $("#new_people").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").hide();

    var number = $("#number").val();
    var name = $("#name").val();
    var department = $("#department").val();
    if (number === "") {
      document.getElementById("numbererror2").style.display = "block";
      document.getElementById("number").classList.add("is-invalid");
      document.getElementById("number").focus();
      return false;
    } else if (name === "") {
      document.getElementById("nameerror").style.display = "block";
      document.getElementById("name").classList.add("is-invalid");
      document.getElementById("name").focus();
      return false;
    } else if (department === "") {
      document.getElementById("departmenterror").style.display = "block";
      document.getElementById("department").classList.add("is-invalid");
      document.getElementById("department").focus();
      return false;
    }
    $.ajax({
      type: "POST",
      url: "new",
      data: {
        number: number,
        name: name,
        department: department,
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
        var mess =
          Lang.get("templateWords.newPInfo") +
          Lang.get("loginPageLang.success");
        alert(mess);
        window.location.reload();
      },
      error: function (err) {
        //job number not 9
        if (err.status == 420) {
          document.getElementById("numbererror1").style.display = "block";
          document.getElementById("number").classList.add("is-invalid");
          document.getElementById("number").value = "";
          document.getElementById("number").focus();
          return false;
        }
        //job number is repeat
        else if (err.status == 421) {
          document.getElementById("numbererror").style.display = "block";
          document.getElementById("number").classList.add("is-invalid");
          document.getElementById("number").value = "";
          document.getElementById("number").focus();
          return false;
        }
      },
    });
  });
});
