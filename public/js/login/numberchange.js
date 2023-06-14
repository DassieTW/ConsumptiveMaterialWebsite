$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

$(document).ready(function () {
  $("#searchnumber").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var select = $(document.activeElement).val();

    var number = [];
    var check = [];
    var newname;
    var newnumber;
    var newdep;
    var checked;

    $("input:checkbox[name=innumber]:checked").each(function () {
      check.push($(this).val());
    });

    var count = check.length;

    for (let i = 0; i < count; i++) {
      number.push($("#number" + check[i]).val());
    }

    if (select == "刪除" || select == "删除" || select == "Delete") {
      select = "刪除";
      checked = $("input[type=checkbox]:checked").length;

      if (!checked) {
        // alert(Lang.get('loginPageLang.nocheck'));
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
        //return false;
      }
    } else {
      select = "新增";
      newname = $("#newname").val();
      newnumber = $("#newnumber").val();
      newdep = $("#newdep").val();
      if (newnumber === "") {
        notyf.open({
          type: "warning",
          message: Lang.get("loginPageLang.enterjobsearch"),
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
        document.getElementById("newnumber").classList.add("is-invalid");
        document.getElementById("newnumber").value = "";
        document.getElementById("newnumber").focus();
        return false;
      } else if (newnumber.length !== 9) {
        notyf.open({
          type: "warning",
          message: Lang.get("loginPageLang.joblength"),
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
        document.getElementById("newnumber").classList.add("is-invalid");
        document.getElementById("newnumber").value = "";
        document.getElementById("newnumber").focus();
        return false;
      }
      if (newname === "") {
        notyf.open({
          type: "warning",
          message: Lang.get("loginPageLang.entername"),
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
        document.getElementById("newname").classList.add("is-invalid");
        document.getElementById("newname").value = "";
        document.getElementById("newname").focus();
        return false;
      }
      if (newdep === "") {
        notyf.open({
          type: "warning",
          message: Lang.get("loginPageLang.enterdep"),
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
        document.getElementById("newdep").classList.add("is-invalid");
        document.getElementById("newdep").value = "";
        document.getElementById("newdep").focus();
        return false;
      }
    }

    $.ajax({
      type: "POST",
      url: "numberchangeordel",
      data: {
        number: number,
        select: select,
        count: count,
        newname: newname,
        newnumber: newnumber,
        newdep: newdep,
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
        if (data.status == 201) {
          notyf.open({
            type: "success",
            message:
              Lang.get("loginPageLang.user") +
              Lang.get("loginPageLang.new") +
              Lang.get("loginPageLang.success"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });
          setTimeout(function () {
            location.reload();
          }, 1000);
        } else if (data.status == 202) {
          notyf.open({
            types: [
              {
                type: "info",
                background: "blue",
                icon: false,
              },
            ],
            type: "info",
            message:
              Lang.get("loginPageLang.user") +
              Lang.get("loginPageLang.delete") +
              Lang.get("loginPageLang.success"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });
          setTimeout(function () {
            location.reload();
          }, 1000);
        } else if (data.status == 421) {
          notyf.open({
            type: "warning",
            message: Lang.get("loginPageLang.jobrepeat"),
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });
          setTimeout(function () {
            location.reload();
          }, 1000);
        }
      },
      error: function (err) {
        console.log(err);

        notyf.open({
          type: "error",
          message: err.responseJSON.message,
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
        document.getElementById("newnumber").classList.add("is-invalid");
        document.getElementById("newnumber").value = "";
      },
    });
  });
});
