var RowsPerPage = 2;
var tablename = "";
var reIsnArray = [];
var reLocArray = [];
var reStockArray = [];
var reCheckArray = [];
var reCheckResultArray = []; // 盤點 - 庫存 的結果 以此判斷盤盈虧
var reTimeArray = [];
var totalSlides = 0;
var $NotYetChecked = 0;
var $isIsn = false;
var $isLoc = false;

$(function () {
  function arrowChangeToUp() {
    var element;
    element = document.getElementById("showHideBtn");
    element.classList.add("animated", "rubberBand");
    setTimeout(function () {
      element.classList.remove("rubberBand");
    }, 500);
  } // func. arrowChangeToUp

  function arrowChangeToDown() {
    var element;
    element = document.getElementById("showHideBtn");
    element.classList.add("animated", "rubberBand");
    setTimeout(function () {
      element.classList.remove("rubberBand");
    }, 500);
  } // func. arrowChangeToDown

  (function () {
    // starting show on document ready
    if (document.getElementById("toggle-state").checked) {
      $("#toggle-state-text").text(Lang.get("checkInvLang.isn"));
      document
        .getElementById("texBox")
        .setAttribute(
          "placeholder",
          Lang.get("checkInvLang.input_isn_barcode")
        );
    } // if
    else {
      $("#toggle-state-text").text(Lang.get("checkInvLang.loc"));
      document
        .getElementById("texBox")
        .setAttribute(
          "placeholder",
          Lang.get("checkInvLang.input_loc_barcode")
        );
    } // else

    $("#texBox").trigger("focus");

    // console.log($('#serialList li').length); // test
    if ($("#serialList li").length === 0) {
      $("#continueT").text(Lang.get("checkInvLang.no_table_found"));
      $("#continueT")
        .parent()
        .find("ul")
        .append(
          '<li><a class="dropdown-item" id="newTableLink" href="#">' +
            Lang.get("checkInvLang.create_new_table") +
            "</a></li>"
        );
      $("#newTableLink").on("click", function (e) {
        e.preventDefault();
        window.location.href = "/checking/create_new_table";
      });
    } // if
  })();

  $("#toggle-state").on("change", function () {
    // 目標改變
    // 'this' will contain a reference to the checkbox
    if (this.checked) {
      $("#toggle-state-text").text(Lang.get("checkInvLang.isn"));
      document
        .getElementById("texBox")
        .setAttribute(
          "placeholder",
          Lang.get("checkInvLang.input_isn_barcode")
        );
    } else {
      $("#toggle-state-text").text(Lang.get("checkInvLang.loc"));
      document
        .getElementById("texBox")
        .setAttribute(
          "placeholder",
          Lang.get("checkInvLang.input_loc_barcode")
        );
    } // if else

    $("#texBox").trigger("focus");
  }); // 目標改變

  $(".serialNum").on("click", function (e) {
    $(".serialNum").removeClass("active"); // remove all active class name
    $("#continueT").text($(this).text());
    var clickedTableName = $("#continueT").text();
    $(".serialNum").each(function (i, obj) {
      // console.log($(this).text()); // test
      if ($(this).text() === $("#continueT").text()) {
        $(this).addClass("active"); // add the active class name
      } // if
    });
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    $.ajax({
      type: "post",
      url: "/checking/set_wanted_table",
      data: { tableName: clickedTableName },
      dataType: "json", // let's set the expected response format
      beforeSend: function () {
        $("body").loadingModal({
          text: "Loading...",
          animation: "circle",
        });
      },
      complete: function () {
        $("body").loadingModal("hide");
        $("body").loadingModal("destroy");
      },
      success: function (response) {
        // do nothing
      },
      error: function (err) {
        if (err.status === 420) {
          // if no result
          console.log("set session failed.");
        } // else if
        else {
          console.log(err.status); // test
        } // else
      }, // error
    }); // end of ajax

    $("#texBox").trigger("focus");
  }); // on drop down menu click

  var tempAll = []; // a two dimentional array for temp scan input
  var tempIsn = [];
  var tempLoc = [];

  $("#inp").on("submit", function (e) {
    //不要自己抓input了 用button讓他們決定是要掃料號or儲位
    e.preventDefault();
    totalSlides = 0;
    $NotYetChecked = 0;

    // clean up previous input results
    $(".message").html(""); // cleanup
    $(".carousel-inner").html(""); // cleanup
    $("#pageCount").html(""); // cleanup
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();
    $(".carousel-inner").html(""); // cleanup

    // --------------------------- for single condition search ---------------------------- //
    var $temp = $("#texBox").val();
    $("#texBox").val(""); // clear input box value
    $isIsn = document.getElementById("toggle-state").checked;
    $isLoc = !$isIsn;
    // -------------------------------------- end------------------------------------------------------------- //

    tablename = $("#continueT").text();
    // console.log(tablename); // test
    reIsnArray = [];
    reLocArray = [];
    reStockArray = [];
    reCheckArray = [];
    reCheckResultArray = [];
    reTimeArray = [];
    reClientArray = [];

    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });
    //--------------------------------------   single conditions --------------------------------- //
    $.ajax({
      type: "post",
      url: "/checking/checkInentdbSearch",
      data: {
        texBox: $temp,
        isIsn: $isIsn,
        tablename: tablename,
        isDetailed: "false",
      },
      dataType: "json", // expected respose datatype from server
      success: function (response) {
        // console.log( response.data ) ; // test
        var myObjs = JSON.parse(JSON.stringify(response.data));
        // console.log(myObjs[0].單號); // test
        var reProductName = [];
        var reSpecification = [];
        var reUnitPrice = [];

        var reCurrency = [];
        var reUnit = [];
        var reMPQ = [];
        var reMOQ = [];

        var reLT = [];
        var reMonthlyBought = [];
        var reGradeAMats = [];

        var reAttribution = [];
        var reProvideDepartment = [];
        var reSafeStock = [];
        for (let a = 0; a < myObjs.length; a++) {
          reIsnArray.push(myObjs[a].料號);
          reLocArray.push(myObjs[a].儲位);
          reStockArray.push(myObjs[a].現有庫存);
          reCheckArray.push(myObjs[a].盤點);
          reTimeArray.push(myObjs[a].updated_at);

          reClientArray.push(myObjs[a].客戶別);
          reProductName.push(myObjs[a].品名);
          reSpecification.push(myObjs[a].規格);
          reUnitPrice.push(myObjs[a].單價);
          reCurrency.push(myObjs[a].幣別);

          reUnit.push(myObjs[a].單位);
          reMPQ.push(myObjs[a].MPQ);
          reMOQ.push(myObjs[a].MOQ);
          reLT.push(myObjs[a].LT);
          reMonthlyBought.push(myObjs[a].月請購);

          reGradeAMats.push(myObjs[a].A級資材);
          // reGPmaterial.push(myObjs[a].GP料件);  //砍了 廠區說不會用到
          reAttribution.push(myObjs[a].耗材歸屬);
          reProvideDepartment.push(myObjs[a].發料部門);
          reSafeStock.push(myObjs[a].安全庫存);

          if (myObjs[a].盤點 === null) {
            reCheckResultArray.push(-999);
          } else {
            reCheckResultArray.push(
              parseInt(myObjs[a].盤點) - parseInt(myObjs[a].現有庫存)
            );
          } // if else
        } // for

        // console.log(reCheckResultArray); // test
        // console.log(reCheckResultArray.length); // test

        // quick sort
        var swap = function (data, i, j) {
          //                        console.log(data); // test
          var tmp = data[i];
          data[i] = data[j];
          data[j] = tmp;
          //                        console.log(data); // test

          tmp = reIsnArray[i];
          reIsnArray[i] = reIsnArray[j];
          reIsnArray[j] = tmp;

          tmp = reLocArray[i];
          reLocArray[i] = reLocArray[j];
          reLocArray[j] = tmp;

          tmp = reStockArray[i];
          reStockArray[i] = reStockArray[j];
          reStockArray[j] = tmp;

          tmp = reCheckArray[i];
          reCheckArray[i] = reCheckArray[j];
          reCheckArray[j] = tmp;

          tmp = reTimeArray[i];
          reTimeArray[i] = reTimeArray[j];
          reTimeArray[j] = tmp;

          tmp = reClientArray[i];
          reClientArray[i] = reClientArray[j];
          reClientArray[j] = tmp;

          tmp = reProductName[i];
          reProductName[i] = reProductName[j];
          reProductName[j] = tmp;

          tmp = reSpecification[i];
          reSpecification[i] = reSpecification[j];
          reSpecification[j] = tmp;

          tmp = reUnitPrice[i];
          reUnitPrice[i] = reUnitPrice[j];
          reUnitPrice[j] = tmp;

          tmp = reCurrency[i];
          reCurrency[i] = reCurrency[j];
          reCurrency[j] = tmp;

          tmp = reUnit[i];
          reUnit[i] = reUnit[j];
          reUnit[j] = tmp;

          tmp = reMPQ[i];
          reMPQ[i] = reMPQ[j];
          reMPQ[j] = tmp;

          tmp = reMOQ[i];
          reMOQ[i] = reMOQ[j];
          reMOQ[j] = tmp;

          tmp = reLT[i];
          reLT[i] = reLT[j];
          reLT[j] = tmp;

          tmp = reMonthlyBought[i];
          reMonthlyBought[i] = reMonthlyBought[j];
          reMonthlyBought[j] = tmp;

          tmp = reGradeAMats[i];
          reGradeAMats[i] = reGradeAMats[j];
          reGradeAMats[j] = tmp;

          // tmp = reGPmaterial[i]; //砍了 廠區說不會用到
          // reGPmaterial[i] = reGPmaterial[j];
          // reGPmaterial[j] = tmp;

          tmp = reAttribution[i];
          reAttribution[i] = reAttribution[j];
          reAttribution[j] = tmp;

          tmp = reProvideDepartment[i];
          reProvideDepartment[i] = reProvideDepartment[j];
          reProvideDepartment[j] = tmp;

          tmp = reSafeStock[i];
          reSafeStock[i] = reSafeStock[j];
          reSafeStock[j] = tmp;
        };

        var partition = function (data, left, right) {
          var pivot = data[right];
          var i = left - 1;
          for (let j = left; j < right; j++) {
            if (data[j] < pivot) {
              i++;
              swap(data, i, j);
            } // if
          } // for
          i++;
          swap(data, i, right);
          return i;
        };

        var quickSort = function (data, left, right) {
          if (left < right) {
            var pivot = partition(data, left, right);
            quickSort(data, left, pivot - 1); // 對左子串列進行快速排序
            quickSort(data, pivot + 1, right); // 對右子串列進行快速排序
          } // if left < right
        };

        quickSort(reCheckResultArray, 0, reCheckResultArray.length - 1);
        // console.log(reCheckArray); // test
        // console.log(reStockArray); // test
        // console.log(reCheckResultArray); // test

        try {
          sessionStorage.setItem("isn", JSON.stringify(reIsnArray));
          sessionStorage.setItem("loc", JSON.stringify(reLocArray));
          sessionStorage.setItem("stock", JSON.stringify(reStockArray));
          sessionStorage.setItem("check", JSON.stringify(reCheckArray));
          sessionStorage.setItem("client", JSON.stringify(reClientArray));
          sessionStorage.setItem("productName", JSON.stringify(reProductName));
          sessionStorage.setItem(
            "specification",
            JSON.stringify(reSpecification)
          );
          sessionStorage.setItem("unitPrice", JSON.stringify(reUnitPrice));
          sessionStorage.setItem("currency", JSON.stringify(reCurrency));
          sessionStorage.setItem("unit", JSON.stringify(reUnit));
          sessionStorage.setItem("mpq", JSON.stringify(reMPQ));
          sessionStorage.setItem("moq", JSON.stringify(reMOQ));
          sessionStorage.setItem("LT", JSON.stringify(reLT));
          sessionStorage.setItem(
            "monthlyBought",
            JSON.stringify(reMonthlyBought)
          );
          sessionStorage.setItem("GradeAMats", JSON.stringify(reGradeAMats));
          // sessionStorage.setItem('GPmaterial', JSON.stringify(reGPmaterial)); //砍了 廠區說不會用到
          sessionStorage.setItem("attribution", JSON.stringify(reAttribution));
          sessionStorage.setItem(
            "provideDepartment",
            JSON.stringify(reProvideDepartment)
          );
          sessionStorage.setItem("safeStock", JSON.stringify(reSafeStock));
          sessionStorage.setItem(
            "safeStock",
            JSON.stringify(reCheckResultArray)
          );
        } catch (e) {
          if (e.code === "22" || e.code === "1024") {
            alert("Quota exceeded!"); //data wasn't successfully saved due to quota exceed so throw an error
          } // if
          else {
            alert(e);
          } // else
        } // try catch

        var wholeThing = "";
        for (let x = 0; x < reIsnArray.length; ) {
          if (x === 0) {
            // set the first slide active
            wholeThing += '<div class="carousel-item active">';
          } else {
            wholeThing += '<div class="carousel-item">';
          } // if else

          for (let y = 0; y < RowsPerPage && x < reIsnArray.length; y++, x++) {
            if (reCheckResultArray[x] === -999) {
              // if 未盤點
              $NotYetChecked++;
              wholeThing +=
                '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #B3E5FC;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                Lang.get("checkInvLang.isn") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                reIsnArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                Lang.get("checkInvLang.product_name") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reProductName[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.loc") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reLocArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.client") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reClientArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                Lang.get("checkInvLang.stock") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reStockArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                Lang.get("checkInvLang.inventory") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid darkblue"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                Lang.get("checkInvLang.checking_result") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid darkblue"\n\
                                               value="' +
                Lang.get("checkInvLang.unknown") +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                Lang.get("checkInvLang.submit") +
                '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                tablename +
                '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
            } // if
            else if (reCheckResultArray[x] === 0) {
              // if 已盤點 且盤正確
              wholeThing +=
                '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #C8E6C9;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                Lang.get("checkInvLang.isn") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                reIsnArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                Lang.get("checkInvLang.product_name") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reProductName[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.loc") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reLocArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.client") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reClientArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                Lang.get("checkInvLang.stock") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reStockArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                Lang.get("checkInvLang.inventory") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid green;"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off" \n\
                                               value="' +
                reCheckArray[x] +
                '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                Lang.get("checkInvLang.exact") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid green"\n\
                                               value="' +
                (reCheckArray[x] - reStockArray[x]) +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                Lang.get("checkInvLang.edit") +
                '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                tablename +
                '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
            } // else if
            else if (reCheckResultArray[x] > 0) {
              // if 已盤點 且盤盈
              wholeThing +=
                '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #FFCDD2;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                Lang.get("checkInvLang.isn") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                reIsnArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                Lang.get("checkInvLang.product_name") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reProductName[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.loc") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reLocArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.client") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reClientArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                Lang.get("checkInvLang.stock") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reStockArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                Lang.get("checkInvLang.inventory") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off" \n\
                                               value="' +
                reCheckArray[x] +
                '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                Lang.get("checkInvLang.excess") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               value="+' +
                (reCheckArray[x] - reStockArray[x]) +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                Lang.get("checkInvLang.edit") +
                '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                tablename +
                '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
            } // else if
            else {
              // if 已盤點 且盤虧
              wholeThing +=
                '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #FFCDD2;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                Lang.get("checkInvLang.isn") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                reIsnArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                Lang.get("checkInvLang.product_name") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reProductName[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.loc") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reLocArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                Lang.get("checkInvLang.client") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reClientArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                Lang.get("checkInvLang.stock") +
                '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                reStockArray[x] +
                '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                Lang.get("checkInvLang.inventory") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off" \n\
                                               value="' +
                reCheckArray[x] +
                '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                Lang.get("checkInvLang.shortage") +
                '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               value="' +
                (reCheckArray[x] - reStockArray[x]) +
                '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                Lang.get("checkInvLang.edit") +
                '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                tablename +
                '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
            } // else if
          } // for
          wholeThing += "</div>";
          totalSlides++;
        } // for

        $(".carousel-inner").html(wholeThing);
        var currentIndex = $("div.active").index() + 1;
        var Namepiece = tablename.split("_");

        if ($isIsn) {
          $(".message").html(
            Namepiece[1] +
              "&nbsp;&nbsp;" +
              Lang.get("checkInvLang.this") +
              '&nbsp;<mark class="col col-auto"><strong class="fs-4">' +
              Lang.get("checkInvLang.isn") +
              "</strong></mark>" +
              Lang.get("checkInvLang.has") +
              '<mark class="col col-auto"><strong class="fs-2" style="color: red">' +
              $NotYetChecked +
              "</strong></mark>" +
              Lang.get("checkInvLang.unchecked")
          );
        } else {
          $(".message").html(
            Namepiece[1] +
              "&nbsp;&nbsp;" +
              Lang.get("checkInvLang.this") +
              '&nbsp;<mark class="col col-auto"><strong class="fs-4">' +
              Lang.get("checkInvLang.loc") +
              "</strong></mark>" +
              Lang.get("checkInvLang.has") +
              '<mark class="col col-auto"><strong class="fs-2" style="color: red">' +
              $NotYetChecked +
              "</strong></mark>" +
              Lang.get("checkInvLang.unchecked")
          );
        } // else

        var tempThings =
          '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="prev"><i class="bi bi-caret-left-fill" style="font-size: 2rem;"></i></a></div>';
        tempThings +=
          '<div class="col col-auto"><button type="button" disabled="disabled" class="btn btn-outline-dark btn-sm col col-auto">' +
          currentIndex +
          "/" +
          totalSlides +
          "</button></div>";
        tempThings +=
          '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="next"><i class="bi bi-caret-right-fill" style="font-size: 2rem;"></i></a></div>';
        $("#pageCount").html(tempThings);
        return false;
      },
      beforeSend: function () {
        $("body").loadingModal({
          text: "Loading...",
          animation: "circle",
        });
      },
      complete: function () {
        $("body").loadingModal("hide");
        $("body").loadingModal("destroy");
      },
      error: function (err) {
        if (err.status === 422) {
          // when status code is 422, it's a validation issue
          // console.log(err.responseJSON.message); // test

          // you can loop through the errors object and show it to the user
          // console.warn(err.responseJSON.errors); // test
          // display errors on each form field
          $.each(err.responseJSON.errors, function (i, error) {
            var el = $(document).find('[name="' + i + '"]');
            // console.log(el.siblings(".input-group-text").length); // test
            el.addClass("is-invalid");
            if (el.siblings(".input-group-text").length > 0) {
              if ($(".invalid-feedback").length === 0) {
                el.parent().after(
                  $(
                    '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                      error[0] +
                      "</strong></span>"
                  )
                );
              } // if
            } // if
            else {
              el.after(
                $(
                  '<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                    error[0] +
                    "</strong></span>"
                )
              );
            } // if else
          });
        } // if error 422
        else if (err.status === 420) {
          // else if error 420
          $("#texBox").addClass("is-invalid");
          if ($isIsn) {
            $("#texBox").after(
              $(
                '<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("checkInvLang.no_such_isn") +
                  "</strong></span>"
              )
            );
          } else {
            $("#texBox").after(
              $(
                '<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                  Lang.get("checkInvLang.no_such_loc") +
                  "</strong></span>"
              )
            );
          } // else
        } // else
        else {
          // Lang = new Lang();
          console.log(err.status); // test
        } // else
      }, // error
    }); // ajax
    //--------------------------------------   end               --------------------------------- //

    $([document.documentElement, document.body]).animate(
      {
        scrollTop: $("#carouselExampleSlidesOnly").offset().top,
      },
      300
    );
    return false;
  }); // on submit

  $("#carouselExampleSlidesOnly").on("slid.bs.carousel", function (e) {
    //        console.log("slid !"); // test
    var currentIndex = $("div.active").index() + 1;
    var tempThings =
      '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="prev"><i class="bi bi-caret-left-fill" style="font-size: 2rem;"></i></a></div>';
    tempThings +=
      '<div class="col col-auto"><button type="button" disabled="disabled" class="btn btn-outline-dark btn-sm col col-auto">' +
      currentIndex +
      "/" +
      totalSlides +
      "</button></div>";
    tempThings +=
      '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="next"><i class="bi bi-caret-right-fill" style="font-size: 2rem;"></i></a></div>';
    $("#pageCount").html(tempThings);
    $([document.documentElement, document.body]).animate(
      {
        scrollTop: $("#carouselExampleSlidesOnly").offset().top,
      },
      300
    );
  });

  $("#carouselExampleSlidesOnly").on("submit", ".updateForm", function (event) {
    event.preventDefault();

    // cleanup previous submit
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var $isnn = $(this).find(".isnForm").val();
    var $locc = $(this).find(".locForm").val();
    var $stock = $(this).find(".stockForm").val();
    var $checkk = parseInt($(this).find(".checkForm").val());
    var $client = $(this).find(".clientForm").val();
    var $tname = $(this).find(".tname").val();
    var $pageno = "1";

    // console.log("isn : " + $(this).find('.isnForm').prop('nodeName')); // test
    // console.log("loc : " + $locc); // test
    // console.log("stock : " + $stock); // test
    // console.log("check : " + $checkk); // test

    var $w = JSON.parse(sessionStorage.getItem("isn"));
    var $x = JSON.parse(sessionStorage.getItem("loc"));
    var $y = JSON.parse(sessionStorage.getItem("stock"));
    var $z = JSON.parse(sessionStorage.getItem("check"));
    var $zz = JSON.parse(sessionStorage.getItem("client"));
    var sendd = false;
    var hasChecked = false;
    var indexC = -1;

    for (let i = 0; i < $w.length; i++) {
      // find the index of current input
      if (
        $w[i] === $isnn &&
        $x[i] === $locc &&
        parseInt($y[i]) === parseInt($stock) &&
        $zz[i] === $client
      ) {
        indexC = i;
        break;
      } // if
    } // for

    if (indexC >= 0 && $z[indexC] !== null) {
      hasChecked = true;
    } // if

    if (indexC >= 0 && parseInt($z[indexC]) === parseInt($checkk)) {
      sendd = confirm(Lang.get("checkInvLang.value_not_changed"));
    } // if
    else {
      sendd = true;
    } // else

    var that = this;
    if (sendd !== true) {
      return false;
    } else {
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
      });
      $.ajax({
        type: "post",
        url: "/checking/updateChecking",
        dataType: "json", // expected respose datatype from server
        data: {
          isnn: $isnn,
          locc: $locc,
          checkk: $checkk,
          clientt: $client,
          pageno: $pageno,
          tname: $tname,
        },
        success: function (response) {
          if (hasChecked === false) {
            $NotYetChecked--;
          } // if

          var Namepiece = tablename.split("_");
          if ($isIsn) {
            $(".message").html(
              Namepiece[1] +
                "&nbsp;&nbsp;" +
                Lang.get("checkInvLang.this") +
                '&nbsp;<mark class="col col-auto"><strong class="fs-4">' +
                Lang.get("checkInvLang.isn") +
                "</strong></mark>" +
                Lang.get("checkInvLang.has") +
                '<mark class="col col-auto"><strong class="fs-2" style="color: red">' +
                $NotYetChecked +
                "</strong></mark>" +
                Lang.get("checkInvLang.unchecked")
            );
          } else {
            $(".message").html(
              Namepiece[1] +
                "&nbsp;&nbsp;" +
                Lang.get("checkInvLang.this") +
                '&nbsp;<mark class="col col-auto"><strong class="fs-4">' +
                Lang.get("checkInvLang.loc") +
                "</strong></mark>" +
                Lang.get("checkInvLang.has") +
                '<mark class="col col-auto"><strong class="fs-2" style="color: red">' +
                $NotYetChecked +
                "</strong></mark>" +
                Lang.get("checkInvLang.unchecked")
            );
          } // else
          $z[indexC] = $checkk;
          sessionStorage.setItem("check", JSON.stringify($z));
          let checkResult = parseInt($checkk) - parseInt($y[indexC]);
          let tempStr = "";
          if (checkResult <= 0) {
            $(that).find(".checkForm").attr("value", $checkk);
            $(that).find(".checkForm").val($checkk);
            $(that).find(".checkForm2").attr("value", checkResult);
            $(that).find(".checkForm2").val(checkResult);
          } else {
            tempStr = "+" + checkResult;
            $(that).find(".checkForm").attr("value", $checkk);
            $(that).find(".checkForm").val($checkk);
            $(that).find(".checkForm2").attr("value", tempStr);
            $(that).find(".checkForm2").val(tempStr);
          } // else

          if (checkResult === 0) {
            $(that).find(".changeOnUpdate").css("background-color", "#C8E6C9");
            $(that).find(".checkForm").css("border", "3px solid green");
            $(that).find(".checkForm2").css("border", "3px solid green");
            $(that)
              .find(".changeLab")
              .html("&nbsp;" + Lang.get("checkInvLang.exact") + "&nbsp;");
            $(that).find(".changeBtnText").html(Lang.get("checkInvLang.edit"));
          } else if (checkResult > 0) {
            $(that).find(".changeOnUpdate").css("background-color", "#FFCDD2");
            $(that).find(".checkForm").css("border", "3px solid red");
            $(that).find(".checkForm2").css("border", "3px solid red");
            $(that)
              .find(".changeLab")
              .html("&nbsp;" + Lang.get("checkInvLang.excess") + "&nbsp;");
            $(that).find(".changeBtnText").html(Lang.get("checkInvLang.edit"));
          } else if (checkResult < 0) {
            $(that).find(".changeOnUpdate").css("background-color", "#FFCDD2");
            $(that).find(".checkForm").css("border", "3px solid red");
            $(that).find(".checkForm2").css("border", "3px solid red");
            $(that)
              .find(".changeLab")
              .html("&nbsp;" + Lang.get("checkInvLang.shortage") + "&nbsp;");
            $(that).find(".changeBtnText").html(Lang.get("checkInvLang.edit"));
          } // if else if

          notyf.success({
            message: Lang.get("checkInvLang.update_success"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });
          return false;
        },
        beforeSend: function () {
          $("body").loadingModal({
            text: "Loading...",
            animation: "circle",
          });
        },
        complete: function () {
          $("body").loadingModal("hide");
          $("body").loadingModal("destroy");
        },
        error: function (err) {
          if (err.status === 422) {
            // when status code is 422, it's a validation issue
            // console.log(err); // test
            $.each(err.responseJSON.errors, function (i, error) {
              // var el = $(document).find('[name="' + i + '"]');
              $(that).find(".checkForm").addClass("is-invalid");
              $(that)
                .find(".checkForm")
                .after(
                  $(
                    '<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                      error[0] +
                      "</strong></span>"
                  )
                );
            });
          } // if error 422
          else if (err.status === 420) {
            // else if error 420
            $(that).find(".checkForm").addClass("is-invalid");
            notyf.error({
              message: Lang.get("checkInvLang.update_failed"),
              duration: 3000, //miliseconds, use 0 for infinite duration
              ripple: true,
              dismissible: true,
              position: {
                x: "right",
                y: "bottom",
              },
            });
          } // else
          else {
            // Lang = new Lang();
            console.log(err.status); // test
          } // else
        }, // error
      }); // ajax
    } // else
  }); // on submit

  $("#carouselExampleSlidesOnly").on("click", ".confirmation", function (e) {
    $(this).parent().parent().parent().parent().trigger("submit");
    //         console.log("Node Name : " + $(this).parent().parent().parent().parent().prop('nodeName') ); // test
    return false;
  });

  $("#hidingClass").on("hide.bs.collapse", function (e) {
    arrowChangeToDown();
  });

  $("#hidingClass").on("show.bs.collapse", function (e) {
    arrowChangeToUp();
  });

  $("#hidingClass").on("shown.bs.collapse", function (e) {
    $("#texBox").trigger("focus");
  });
}); // on document ready

$(window).on("load", function () {
  (function () {
    // starting show on document ready
    var sheetIDFromResultPage = getUrlParameter("sheet");
    var locFromResultPage = getUrlParameter("loc");
    var isnFromResultPage = getUrlParameter("isn");

    // console.log(sheetIDFromResultPage); // test
    // console.log(locFromResultPage); // test
    // console.log(isnFromResultPage); // test

    // clean up the url parameter, "replaceState" will NOT add the new url to history,
    // while using "pushState" will add/inject this edited url to history entries
    window.history.replaceState({}, document.title, "/checking");

    if (sheetIDFromResultPage !== false) {
      $("#continueT").text(sheetIDFromResultPage);
      $(".serialNum").removeClass("active");
      $(".serialNum").each(function (i, obj) {
        if ($(this).text() === sheetIDFromResultPage) {
          $(this).addClass("active");
          return false; // break the each loop
        } // if
      }); // for each

      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
      });
      $.ajax({
        type: "post",
        url: "/checking/set_wanted_table",
        data: { tableName: sheetIDFromResultPage },
        dataType: "json", // let's set the expected response format
        beforeSend: function () {
          $("body").loadingModal({
            text: "Loading...",
            animation: "circle",
          });
        },
        complete: function () {
          $("body").loadingModal("hide");
          $("body").loadingModal("destroy");
        },
        success: function (response) {
          // do nothing
        },
        error: function (err) {
          if (err.status === 420) {
            // if no result
            console.log("set session failed.");
          } // else if
          else {
            console.log(err.status); // test
          } // else
        }, // error
      }); // end of ajax

      totalSlides = 0;
      $NotYetChecked = 0;

      // clean up previous input results
      $(".message").html(""); // cleanup
      $(".carousel-inner").html(""); // cleanup
      $("#pageCount").html(""); // cleanup
      $(".is-invalid").removeClass("is-invalid");
      $(".invalid-feedback").remove();
      $(".carousel-inner").html(""); // cleanup

      // --------------------------- for single condition search ---------------------------- //
      var $temp = $("#texBox").val();
      $("#texBox").val(""); // clear input box value
      // -------------------------------------- end------------------------------------------------------------- //

      tablename = sheetIDFromResultPage;
      // console.log(tablename); // test
      reIsnArray = [];
      reLocArray = [];
      reStockArray = [];
      reCheckArray = [];
      reCheckResultArray = [];
      reTimeArray = [];
      reClientArray = [];

      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
      });
      //--------------------------------------   single conditions --------------------------------- //
      $.ajax({
        type: "post",
        url: "/checking/checkInentdbSearch",
        data: {
          tablename: sheetIDFromResultPage,
          isn: isnFromResultPage,
          loc: locFromResultPage,
          isDetailed: "true",
        },
        dataType: "json", // expected respose datatype from server
        success: function (response) {
          // console.log( response.data ) ; // test
          var myObjs = JSON.parse(JSON.stringify(response.data));
          // console.log(myObjs[0].單號); // test
          var reProductName = [];
          var reSpecification = [];
          var reUnitPrice = [];

          var reCurrency = [];
          var reUnit = [];
          var reMPQ = [];
          var reMOQ = [];

          var reLT = [];
          var reMonthlyBought = [];
          var reGradeAMats = [];

          var reAttribution = [];
          var reProvideDepartment = [];
          var reSafeStock = [];
          for (let a = 0; a < myObjs.length; a++) {
            reIsnArray.push(myObjs[a].料號);
            reLocArray.push(myObjs[a].儲位);
            reStockArray.push(myObjs[a].現有庫存);
            reCheckArray.push(myObjs[a].盤點);
            reTimeArray.push(myObjs[a].updated_at);

            reClientArray.push(myObjs[a].客戶別);
            reProductName.push(myObjs[a].品名);
            reSpecification.push(myObjs[a].規格);
            reUnitPrice.push(myObjs[a].單價);
            reCurrency.push(myObjs[a].幣別);

            reUnit.push(myObjs[a].單位);
            reMPQ.push(myObjs[a].MPQ);
            reMOQ.push(myObjs[a].MOQ);
            reLT.push(myObjs[a].LT);
            reMonthlyBought.push(myObjs[a].月請購);

            reGradeAMats.push(myObjs[a].A級資材);
            // reGPmaterial.push(myObjs[a].GP料件);  //砍了 廠區說不會用到
            reAttribution.push(myObjs[a].耗材歸屬);
            reProvideDepartment.push(myObjs[a].發料部門);
            reSafeStock.push(myObjs[a].安全庫存);

            if (myObjs[a].盤點 === null) {
              reCheckResultArray.push(-999);
            } else {
              reCheckResultArray.push(
                parseInt(myObjs[a].盤點) - parseInt(myObjs[a].現有庫存)
              );
            } // if else
          } // for

          // console.log(reCheckResultArray); // test
          // console.log(reCheckResultArray.length); // test

          // quick sort
          var swap = function (data, i, j) {
            //                        console.log(data); // test
            var tmp = data[i];
            data[i] = data[j];
            data[j] = tmp;
            //                        console.log(data); // test

            tmp = reIsnArray[i];
            reIsnArray[i] = reIsnArray[j];
            reIsnArray[j] = tmp;

            tmp = reLocArray[i];
            reLocArray[i] = reLocArray[j];
            reLocArray[j] = tmp;

            tmp = reStockArray[i];
            reStockArray[i] = reStockArray[j];
            reStockArray[j] = tmp;

            tmp = reCheckArray[i];
            reCheckArray[i] = reCheckArray[j];
            reCheckArray[j] = tmp;

            tmp = reTimeArray[i];
            reTimeArray[i] = reTimeArray[j];
            reTimeArray[j] = tmp;

            tmp = reClientArray[i];
            reClientArray[i] = reClientArray[j];
            reClientArray[j] = tmp;

            tmp = reProductName[i];
            reProductName[i] = reProductName[j];
            reProductName[j] = tmp;

            tmp = reSpecification[i];
            reSpecification[i] = reSpecification[j];
            reSpecification[j] = tmp;

            tmp = reUnitPrice[i];
            reUnitPrice[i] = reUnitPrice[j];
            reUnitPrice[j] = tmp;

            tmp = reCurrency[i];
            reCurrency[i] = reCurrency[j];
            reCurrency[j] = tmp;

            tmp = reUnit[i];
            reUnit[i] = reUnit[j];
            reUnit[j] = tmp;

            tmp = reMPQ[i];
            reMPQ[i] = reMPQ[j];
            reMPQ[j] = tmp;

            tmp = reMOQ[i];
            reMOQ[i] = reMOQ[j];
            reMOQ[j] = tmp;

            tmp = reLT[i];
            reLT[i] = reLT[j];
            reLT[j] = tmp;

            tmp = reMonthlyBought[i];
            reMonthlyBought[i] = reMonthlyBought[j];
            reMonthlyBought[j] = tmp;

            tmp = reGradeAMats[i];
            reGradeAMats[i] = reGradeAMats[j];
            reGradeAMats[j] = tmp;

            // tmp = reGPmaterial[i]; //砍了 廠區說不會用到
            // reGPmaterial[i] = reGPmaterial[j];
            // reGPmaterial[j] = tmp;

            tmp = reAttribution[i];
            reAttribution[i] = reAttribution[j];
            reAttribution[j] = tmp;

            tmp = reProvideDepartment[i];
            reProvideDepartment[i] = reProvideDepartment[j];
            reProvideDepartment[j] = tmp;

            tmp = reSafeStock[i];
            reSafeStock[i] = reSafeStock[j];
            reSafeStock[j] = tmp;
          };

          var partition = function (data, left, right) {
            var pivot = data[right];
            var i = left - 1;
            for (let j = left; j < right; j++) {
              if (data[j] < pivot) {
                i++;
                swap(data, i, j);
              } // if
            } // for
            i++;
            swap(data, i, right);
            return i;
          };

          var quickSort = function (data, left, right) {
            if (left < right) {
              var pivot = partition(data, left, right);
              quickSort(data, left, pivot - 1); // 對左子串列進行快速排序
              quickSort(data, pivot + 1, right); // 對右子串列進行快速排序
            } // if left < right
          };

          quickSort(reCheckResultArray, 0, reCheckResultArray.length - 1);
          // console.log(reCheckArray); // test
          // console.log(reStockArray); // test
          // console.log(reCheckResultArray); // test

          try {
            sessionStorage.setItem("isn", JSON.stringify(reIsnArray));
            sessionStorage.setItem("loc", JSON.stringify(reLocArray));
            sessionStorage.setItem("stock", JSON.stringify(reStockArray));
            sessionStorage.setItem("check", JSON.stringify(reCheckArray));
            sessionStorage.setItem("client", JSON.stringify(reClientArray));
            sessionStorage.setItem(
              "productName",
              JSON.stringify(reProductName)
            );
            sessionStorage.setItem(
              "specification",
              JSON.stringify(reSpecification)
            );
            sessionStorage.setItem("unitPrice", JSON.stringify(reUnitPrice));
            sessionStorage.setItem("currency", JSON.stringify(reCurrency));
            sessionStorage.setItem("unit", JSON.stringify(reUnit));
            sessionStorage.setItem("mpq", JSON.stringify(reMPQ));
            sessionStorage.setItem("moq", JSON.stringify(reMOQ));
            sessionStorage.setItem("LT", JSON.stringify(reLT));
            sessionStorage.setItem(
              "monthlyBought",
              JSON.stringify(reMonthlyBought)
            );
            sessionStorage.setItem("GradeAMats", JSON.stringify(reGradeAMats));
            // sessionStorage.setItem('GPmaterial', JSON.stringify(reGPmaterial)); //砍了 廠區說不會用到
            sessionStorage.setItem(
              "attribution",
              JSON.stringify(reAttribution)
            );
            sessionStorage.setItem(
              "provideDepartment",
              JSON.stringify(reProvideDepartment)
            );
            sessionStorage.setItem("safeStock", JSON.stringify(reSafeStock));
            sessionStorage.setItem(
              "safeStock",
              JSON.stringify(reCheckResultArray)
            );
          } catch (e) {
            if (e.code === "22" || e.code === "1024") {
              alert("Quota exceeded!"); //data wasn't successfully saved due to quota exceed so throw an error
            } // if
            else {
              alert(e);
            } // else
          } // try catch

          var wholeThing = "";
          for (let x = 0; x < reIsnArray.length; ) {
            if (x === 0) {
              // set the first slide active
              wholeThing += '<div class="carousel-item active">';
            } else {
              wholeThing += '<div class="carousel-item">';
            } // if else

            for (
              let y = 0;
              y < RowsPerPage && x < reIsnArray.length;
              y++, x++
            ) {
              if (reCheckResultArray[x] === -999) {
                // if 未盤點
                $NotYetChecked++;
                wholeThing +=
                  '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #B3E5FC;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                  Lang.get("checkInvLang.isn") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                  reIsnArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                  Lang.get("checkInvLang.product_name") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reProductName[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.loc") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reLocArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.client") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reClientArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                  Lang.get("checkInvLang.stock") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reStockArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                  Lang.get("checkInvLang.inventory") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid darkblue"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                  Lang.get("checkInvLang.checking_result") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid darkblue"\n\
                                               value="' +
                  Lang.get("checkInvLang.unknown") +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                  Lang.get("checkInvLang.submit") +
                  '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                  tablename +
                  '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
              } // if
              else if (reCheckResultArray[x] === 0) {
                // if 已盤點 且盤正確
                wholeThing +=
                  '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #C8E6C9;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                  Lang.get("checkInvLang.isn") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                  reIsnArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                  Lang.get("checkInvLang.product_name") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reProductName[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.loc") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reLocArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.client") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reClientArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                  Lang.get("checkInvLang.stock") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reStockArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                  Lang.get("checkInvLang.inventory") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid green;"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off" \n\
                                               value="' +
                  reCheckArray[x] +
                  '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                  Lang.get("checkInvLang.exact") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid green"\n\
                                               value="' +
                  (reCheckArray[x] - reStockArray[x]) +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                  Lang.get("checkInvLang.edit") +
                  '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                  tablename +
                  '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
              } // else if
              else if (reCheckResultArray[x] > 0) {
                // if 已盤點 且盤盈
                wholeThing +=
                  '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #FFCDD2;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                  Lang.get("checkInvLang.isn") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                  reIsnArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                  Lang.get("checkInvLang.product_name") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reProductName[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.loc") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reLocArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.client") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reClientArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                  Lang.get("checkInvLang.stock") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reStockArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                  Lang.get("checkInvLang.inventory") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off" \n\
                                               value="' +
                  reCheckArray[x] +
                  '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                  Lang.get("checkInvLang.excess") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               value="+' +
                  (reCheckArray[x] - reStockArray[x]) +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                  Lang.get("checkInvLang.edit") +
                  '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                  tablename +
                  '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
              } // else if
              else {
                // if 已盤點 且盤虧
                wholeThing +=
                  '\
                    <form action="" class="updateForm needs-validation mb-0" novalidate autocomplete="off" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate m-0 p-0" style="background-color: #FFCDD2;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch ps-2">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">' +
                  Lang.get("checkInvLang.isn") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' +
                  reIsnArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">' +
                  Lang.get("checkInvLang.product_name") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reProductName[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.loc") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reLocArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">' +
                  Lang.get("checkInvLang.client") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reClientArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">' +
                  Lang.get("checkInvLang.stock") +
                  '：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' +
                  reStockArray[x] +
                  '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto">&nbsp;' +
                  Lang.get("checkInvLang.inventory") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               required \n\
                                               pattern="[-+]?d*" \n\
                                               autocomplete="off" \n\
                                               value="' +
                  reCheckArray[x] +
                  '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;' +
                  Lang.get("checkInvLang.shortage") +
                  '&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="form-control align-self-center checkForm2"\n\
                                               style="text-align: center; width: 5ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               value="' +
                  (reCheckArray[x] - reStockArray[x]) +
                  '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-secondary btn-sm">\n\
                                            <i class="bi bi-check-lg"></i>\n\
                                            <strong class="changeBtnText">' +
                  Lang.get("checkInvLang.edit") +
                  '</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' +
                  tablename +
                  '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
              } // else if
            } // for
            wholeThing += "</div>";
            totalSlides++;
          } // for

          $(".carousel-inner").html(wholeThing);
          var currentIndex = $("div.active").index() + 1;
          var Namepiece = tablename.split("_");

          $(".message").html(Namepiece[1]);

          var tempThings =
            '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="prev"><i class="bi bi-caret-left-fill" style="font-size: 2rem;"></i></a></div>';
          tempThings +=
            '<div class="col col-auto"><button type="button" disabled="disabled" class="btn btn-outline-dark btn-sm col col-auto">' +
            currentIndex +
            "/" +
            totalSlides +
            "</button></div>";
          tempThings +=
            '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="next"><i class="bi bi-caret-right-fill" style="font-size: 2rem;"></i></a></div>';
          $("#pageCount").html(tempThings);
          return false;
        },
        beforeSend: function () {
          $("body").loadingModal({
            text: "Loading...",
            animation: "circle",
          });
        },
        complete: function () {
          $("body").loadingModal("hide");
          $("body").loadingModal("destroy");
        },
        error: function (err) {
          if (err.status === 422) {
            // when status code is 422, it's a validation issue
            // console.log(err.responseJSON.message); // test

            // you can loop through the errors object and show it to the user
            // console.warn(err.responseJSON.errors); // test
            // display errors on each form field
            $.each(err.responseJSON.errors, function (i, error) {
              var el = $(document).find('[name="' + i + '"]');
              // console.log(el.siblings(".input-group-text").length); // test
              el.addClass("is-invalid");
              if (el.siblings(".input-group-text").length > 0) {
                if ($(".invalid-feedback").length === 0) {
                  el.parent().after(
                    $(
                      '<span class="invalid-feedback p-0 m-0" role="alert"><strong>' +
                        error[0] +
                        "</strong></span>"
                    )
                  );
                } // if
              } // if
              else {
                el.after(
                  $(
                    '<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                      error[0] +
                      "</strong></span>"
                  )
                );
              } // if else
            });
          } // if error 422
          else if (err.status === 420) {
            // else if error 420
            $("#texBox").addClass("is-invalid");
            if ($isIsn) {
              $("#texBox").after(
                $(
                  '<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                    Lang.get("checkInvLang.no_such_isn") +
                    "</strong></span>"
                )
              );
            } else {
              $("#texBox").after(
                $(
                  '<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                    Lang.get("checkInvLang.no_such_loc") +
                    "</strong></span>"
                )
              );
            } // else
          } // else
          else {
            // Lang = new Lang();
            console.log(err.status); // test
          } // else
        }, // error
      }); // ajax
      //--------------------------------------   end               --------------------------------- //

      $([document.documentElement, document.body]).animate(
        {
          scrollTop: $("#carouselExampleSlidesOnly").offset().top,
        },
        300
      );
    } // if
  })();
});
