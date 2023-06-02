$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  var options = ["1", "2", "3", "4"];
  var all = $("#count").val();
  console.log(all);
  for (let i = 0; i < all; i++) {
    var selectElement = document.getElementById("priority" + i);
    var temppriority = $("#pr" + i).val();
    for (let j = 0; j < options.length; j++) {
      if (temppriority !== options[j]) {
        var option = document.createElement("option");
        option.value = options[j];
        option.text = options[j];
        selectElement.appendChild(option);
      }
    }
  }
  $("#searchusername").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var username = [];
    var check = [];
    var priority = [];

    $("input:checkbox[name=innumber]:checked").each(function () {
      check.push($(this).val());
    });

    var count = check.length;

    for (let i = 0; i < count; i++) {
      username.push($("#username" + check[i]).val());
      priority.push($("#priority" + check[i]).val());
    }

    checked = $("input[type=checkbox]:checked").length;

    if (!checked) {
      notyf.open({
        type: "warning",
        message: Lang.get("loginPageLang.nocheck"),
        duration: 3000, //miliseconds, use 0 for infinite duration
        ripple: true,
        dismissible: true,
        position: {
          x: "right",
          y: "bottom",
        },
      });
      return false;
    }

    $.ajax({
      type: "POST",
      url: "usernamechangeordel",
      data: {
        username: username,
        priority: priority,
        count: count,
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
        console.log(data);

        var mess =
          Lang.get("loginPageLang.total") +
          " " +
          data.message +
          " " +
          Lang.get("loginPageLang.record") +
          Lang.get("loginPageLang.user") +
          Lang.get("loginPageLang.change") +
          Lang.get("loginPageLang.success");
        alert(mess);
        window.location.href = "username";
      },
      error: function (err) {
        console.log(err);

        var mess = err.responseJSON.message;
        alert(mess);
      },
    });
  });
});
