function appenSVg() {
  var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
  var path = document.createElementNS("http://www.w3.org/2000/svg", "path");
  path.setAttribute(
    "d",
    "M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"
  );
  svg.setAttribute("width", "16");
  svg.setAttribute("height", "16");
  svg.setAttribute("fill", "#c94466");
  svg.setAttribute("class", "bi bi-x-circle-fill");
  svg.setAttribute("viewBox", "0 0 16 16");
  svg.appendChild(path);
  $(".deleteBtn").append(svg);
  $(".deleteBtn").on("click", function (e) {
    e.preventDefault();
    if (sessionStorage.getItem("isnArray") !== null) {
      var isnArray = JSON.parse(sessionStorage.getItem("isnArray"));
      var isnName = JSON.parse(sessionStorage.getItem("isnName"));
      var isnCount = JSON.parse(sessionStorage.getItem("isnCount"));
      var isnSepCount = JSON.parse(sessionStorage.getItem("isnSepCount"));

      var str = $(this).attr("id");
      const myArr = str.split("__");
      // console.log(myArr); // test
      // console.log(myArr.length); // test
      let index1 = -1;
      $.grep(isnArray, function (isn, index) {
        // console.log(isnName[index]); // test
        if (isn === myArr[0] && isnName[index] === myArr[1]) {
          index1 = index;
          return true;
        } // if
        else {
          return false;
        } // else
      });

      if (index1 !== -1) {
        // delete a isn
        isnArray.splice(index1, 1);
        isnName.splice(index1, 1);
        isnSepCount.splice(index1, 1);
        isnCount = isnCount - 1;
        sessionStorage.setItem("isnArray", JSON.stringify(isnArray));
        sessionStorage.setItem("isnName", JSON.stringify(isnName));
        sessionStorage.setItem("isnCount", JSON.stringify(isnCount));
        sessionStorage.setItem("isnSepCount", JSON.stringify(isnSepCount));
      } // if
    } // if isnArray is set

    if (sessionStorage.getItem("locArray") !== null) {
      var locArray = JSON.parse(sessionStorage.getItem("locArray"));
      var locCount = JSON.parse(sessionStorage.getItem("locCount"));
      var locSepCount = JSON.parse(sessionStorage.getItem("locSepCount"));
      var str = $(this).attr("id");
      let index2 = $.inArray(str, locArray);
      if (index2 !== -1) {
        // delete a loc
        locArray.splice(index2, 1);
        locSepCount.splice(index2, 1);
        locCount = locCount - 1;
        sessionStorage.setItem("locArray", JSON.stringify(locArray));
        sessionStorage.setItem("locCount", JSON.stringify(locCount));
        sessionStorage.setItem("locSepCount", JSON.stringify(locSepCount));
      } // if
    } // if locArray is set

    $(this).parent().parent().remove();
  }); // on delete btn click

  $(".printNum").on("input", function (e) {
    //restrict input to numbers
    this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");
    if (this.value === "") {
      this.value = 0;
    } // if

    var isnArray = JSON.parse(sessionStorage.getItem("isnArray"));
    var isnName = JSON.parse(sessionStorage.getItem("isnName"));

    var isnSepCount = JSON.parse(sessionStorage.getItem("isnSepCount"));

    var strr = $(this).attr("id");
    const myArr = strr.split("__");
    // console.log(myArr); // test
    // console.log(myArr.length); // test
    let index2 = -1;
    $.grep(isnArray, function (isn, index) {
      // console.log(isnName[index]); // test
      if (isn === myArr[0] && isnName[index] === myArr[1]) {
        index2 = index;
        return true;
      } // if
      else {
        return false;
      } // else
    });

    if (index2 !== -1) {
      // update a print count
      isnSepCount[index2] = parseInt($(this).val()) + 0;
      sessionStorage.setItem("isnSepCount", JSON.stringify(isnSepCount));
    } // if
  }); // on printNum input

  $(".printNum2").on("input", function (e) {
    //restrict input to numbers
    this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");
    if (this.value === "") {
      this.value = 0;
    } // if
    var locArray = JSON.parse(sessionStorage.getItem("locArray"));

    var locSepCount = JSON.parse(sessionStorage.getItem("locSepCount"));

    var strr = $(this).attr("id");
    const myArr = strr.split("__");
    // console.log(myArr); // test
    // console.log(myArr.length); // test
    let index2 = -1;
    $.grep(locArray, function (loc, index) {
      // console.log(isnName[index]); // test
      if (loc === myArr[0]) {
        index2 = index;
        return true;
      } // if
      else {
        return false;
      } // else
    });

    if (index2 !== -1) {
      // update a print count
      locSepCount[index2] = parseInt($(this).val()) + 0;
      sessionStorage.setItem("locSepCount", JSON.stringify(locSepCount));
    } // if
  }); // on printNum2 input
} // appenSVg

$(function () {
  $("body").loadingModal({
    text: "Loading...",
    animation: "circle",
  });

  $("#QueryFlag").on("click", function (e) {
    // console.log("clicked!"); // test
    $("body").loadingModal("hide");
    $("body").loadingModal("destroy");
  });

  $("#addBulletinForm").on("submit", function (e) {
    e.preventDefault();
    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();
    var password = $("#password").val();
    var newpassword = $("#newpassword").val();
    var surepassword = $("#surepassword").val();
    $.ajax({
      type: "POST",
      url: "change",
      data: {
        password: password,
        newpassword: newpassword,
        surepassword: surepassword,
      },
      dataType: "json", // expected respose datatype from server
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
          Lang.get("loginPageLang.change") +
          Lang.get("oboundpageLang.success") +
          "，" +
          Lang.get("loginPageLang.againlogin");
        alert(mess);
        //alert('更新成功，請重新登入。');
        window.location.href = "login";
      },
      error: function (err) {
        // 舊密碼輸入錯誤
        if (err.status == 420) {
          document.getElementById("password").classList.add("is-invalid");
          document.getElementById("password").value = "";
          $("#password")
            .siblings(".input-group-text")
            .after(
              $(
                '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("loginPageLang.errorpassword") +
                  "</strong></span>"
              )
            );
        } // if
        // 密碼並不相符
        else if (err.status == 421) {
          document.getElementById("newpassword").classList.add("is-invalid");
          document.getElementById("newpassword").value = "";
          document.getElementById("surepassword").classList.add("is-invalid");
          document.getElementById("surepassword").value = "";
          $("#newpassword")
            .siblings(".input-group-text")
            .after(
              $(
                '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("loginPageLang.errorpassword2") +
                  "</strong></span>"
              )
            );
          $("#surepassword")
            .siblings(".input-group-text")
            .after(
              $(
                '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("loginPageLang.errorpassword2") +
                  "</strong></span>"
              )
            );
        } // else if
        else if (err.status == 422) {
          // when status code is 422, it's a validation issue
          // console.log(err.responseJSON.message); // test

          // you can loop through the errors object and show it to the user
          // console.warn(err.responseJSON.errors); // test
          // display errors on each form field
          $.each(err.responseJSON.errors, function (i, error) {
            var el = $(document).find('[name="' + i + '"]');
            // console.log(el); // test
            el.addClass("is-invalid");
            if (el.siblings(".input-group-text").length > 0) {
              el.siblings(".input-group-text").after(
                $(
                  '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                    error[0] +
                    "</strong></span>"
                )
              );
            } // if
            else {
              el.after(
                $(
                  '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                    error[0] +
                    "</strong></span>"
                )
              );
            } // if else
          });
        } // else if
        else {
          console.log(err.status); // print out other errors
        } // else
      },
    });
  });
}); // on document ready

// $(window).on('beforeunload', function() {
//     sessionStorage.clear();
// });
