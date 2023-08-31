var tab = sessionStorage.getItem("basic");
var choose = sessionStorage.getItem("basic");
if (!tab) {
  tab = "FactoryExample";
}
if (!choose) {
  choose = "FactoryExample";
}
if (tab === "FactoryExample") {
  sessionStorage.setItem("basic1", "showfactory");
}
if (tab === "ClientExample") {
  sessionStorage.setItem("basic1", "showclient");
}
if (tab === "MachineExample") {
  sessionStorage.setItem("basic1", "showmachine");
}
if (tab === "ProductionExample") {
  sessionStorage.setItem("basic1", "showprocess");
}
if (tab === "LineExample") {
  sessionStorage.setItem("basic1", "showline");
}
if (tab === "UseExample") {
  sessionStorage.setItem("basic1", "showusedep");
}
if (tab === "UseReasonExample") {
  sessionStorage.setItem("basic1", "showusereason");
}
if (tab === "InReasonExample") {
  sessionStorage.setItem("basic1", "showinreason");
}
if (tab === "PositionExample") {
  sessionStorage.setItem("basic1", "showloc");
}
if (tab === "SendExample") {
  sessionStorage.setItem("basic1", "showsenddep");
}
if (tab === "OboundExample") {
  sessionStorage.setItem("basic1", "showobound");
}
if (tab === "BackReasonExample") {
  sessionStorage.setItem("basic1", "showreturnreason");
}

var tab1 = sessionStorage.getItem("basic1");
if (!tab1) {
  tab1 = "showfactory";
}
$("#" + tab + "a").addClass("active");
$("#" + tab1).addClass("active");
$("#" + tab1).removeClass("fade");

$(function () {
  $("#myTab").on("scroll", function () {
    // left arrow control
    var $elem = $("#myTab");
    var newScrollLeft = $elem.scrollLeft(),
      width = $elem.outerWidth(),
      scrollWidth = $elem.get(0).scrollWidth;
    if (scrollWidth - newScrollLeft == width) {
      console.log("right end");
    } // if// left arrow control
  });

  $("#download").attr("href", "../download/FactoryExample.xlsx");

  $(".nav-item").on("click", function () {
    choose = this.id;
    sessionStorage.setItem("basic", choose);
    $("#download").attr("href", "../download/" + choose + ".xlsx");
  });

  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $("#basicdata").on("submit", function (e) {
    e.preventDefault();

    // clean up previous input results
    $(".is-invalid").removeClass("is-invalid");
    $(".invalid-feedback").remove();

    var check = [];
    var data = [];
    var olddata = [];
    var select = $(document.activeElement).val();

    if (choose === "FactoryExample") {
      var datacheck = "factorycheck";
      var dataname = "factory";
      var datanew = "factorynew";
    }
    if (choose === "ClientExample") {
      var datacheck = "clientcheck";
      var dataname = "client";
      var datanew = "clientnew";
    }
    if (choose === "MachineExample") {
      var datacheck = "machinecheck";
      var dataname = "machine";
      var datanew = "machinenew";
    }
    if (choose === "ProductionExample") {
      var datacheck = "productioncheck";
      var dataname = "production";
      var datanew = "productionnew";
    }
    if (choose === "LineExample") {
      var datacheck = "linecheck";
      var dataname = "line";
      var datanew = "linenew";
    }
    if (choose === "UseExample") {
      var datacheck = "usecheck";
      var dataname = "use";
      var datanew = "usenew";
    }
    if (choose === "UseReasonExample") {
      var datacheck = "usereasoncheck";
      var dataname = "usereason";
      var datanew = "usereasonnew";
    }
    if (choose === "InReasonExample") {
      var datacheck = "inreasoncheck";
      var dataname = "inreason";
      var datanew = "inreasonnew";
    }
    if (choose === "PositionExample") {
      var datacheck = "positioncheck";
      var dataname = "position";
      var datanew = "positionnew";
    }
    if (choose === "SendExample") {
      var datacheck = "sendcheck";
      var dataname = "send";
      var datanew = "sendnew";
    }
    if (choose === "OboundExample") {
      var datacheck = "ocheck";
      var dataname = "o";
      var datanew = "onew";
    }
    if (choose === "BackReasonExample") {
      var datacheck = "backcheck";
      var dataname = "back";
      var datanew = "backnew";
    }

    $("input:checkbox[name=" + datacheck + "]:checked").each(function () {
      check.push($(this).val());
    });

    if (select === "刪除" || select === "删除" || select === "Delete") {
      select = "刪除";
    }
    if (select === "更新" || select === "Update") {
      select = "更新";
    }

    var count = check.length;

    for (let i = 0; i < count; i++) {
      data.push($("#" + dataname + check[i]).val());
      olddata.push($("#old" + dataname + check[i]).val());
    }
    var dataid = datanew;
    datanew = $("#" + datanew).val();

    var checked = $("input:checkbox[name=" + datacheck + "]:checked").length;

    if (!checked && !datanew) {
      notyf.open({
        type: "warning",
        message: Lang.get("basicInfoLang.nocheck"),
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
      url: "/basic/changeordelete",
      data: {
        select: select,
        data: data,
        datanew: datanew,
        dataname: dataname,
        olddata: olddata,
      },
      dataType: "json", // expected respose datatype from server
      //async: false,

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
      success: function () {
        var mess =
          Lang.get("basicInfoLang.change") +
          " / " +
          Lang.get("basicInfoLang.delete") +
          " " +
          Lang.get("basicInfoLang.success");

        notyf.open({
          type: "success",
          message: mess,
          duration: 3000, //miliseconds, use 0 for infinite duration
          ripple: true,
          dismissible: true,
          position: {
            x: "right",
            y: "bottom",
          },
        });
        setTimeout(() => window.location.reload(), 1500);
      },
      error: function (err) {
        if (err.status === 422) {
          document
            .getElementById(err.responseJSON.message)
            .classList.add("is-invalid");
          var mess = Lang.get("basicInfoLang.repeat");
          notyf.open({
            type: "warning",
            message: mess,
            duration: 3000, //miliseconds, use 0 for infinite duration
            ripple: true,
            dismissible: true,
            position: {
              x: "right",
              y: "bottom",
            },
          });
          //   window.location.reload();
          $("#" + dataid).val("");
        } else {
          var mess = err.responseJSON.message;
          alert(mess);
          window.location.reload();
        }
      },
    });
  });
});

/*$(window).on('unload', function() {
    var a_n = window.event.screenX - window.screenLeft;
    var a_b = a_n > document.documentElement.scrollWidth-20;
    if(a_b && window.event.clientY< 0 || window.event.altKey){

    }else{
        sessionStorage.clear();
    }
});*/
/*window.onbeforeunload = function () {

    var n = window.event.screenX - window.screenLeft;

    var b = n > document.documentElement.scrollWidth - 20;

    if (!(b && window.event.clientY < 0 || window.event.altKey)) {
        //window.event.returnValue = "真的要刷新页面么？";

        //这里放置我想执行缓存的代码
        //sessionStorage.clear();

    }
    else {
        sessionStorage.clear();
    }
}*/
