//show select 退料單號
$("#list").on("change", function () {
  var value = $("#list").val();
  $("#test").find("tr").not("#require").hide();
  var result_style = document.getElementById(value).style;
  result_style.display = "table-row";
  //document.getElementById("test").style.display = "block";
});

$.ajaxSetup({
  headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

function myFunction() {
  var input, filter, ul, li, a, i;
  ul = document.getElementById("receivemenu");
  li = $("li.receiveli");
  input = document.getElementById("pickpeople");
  filter = input.value.toUpperCase();
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("a")[0];
    if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}
function myFunction2() {
  var input, filter, ul, li, a, i;
  ul = document.getElementById("backmenu");
  li = $("li.backli");
  input = document.getElementById("backpeople");
  filter = input.value.toUpperCase();
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("a")[0];
    if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}
$("li.receiveli").on("click", function () {
  var content = $(this).text();
  $("#pickpeople").val(content);
});
$("li.backli").on("click", function () {
  var content = $(this).text();
  $("#backpeople").val(content);
});

$("#backlist").on("submit", function (e) {
  e.preventDefault();
  var count = $("#count").val();
  var list = $("#list").val();
  var advance = $("#advance" + list).html();
  var number = $("#number" + list).html();
  var client = $("#client" + list).html();
  var amount = $("#amount" + list).val();
  var reason = $("#reason" + list).val();
  var pick = $("#pickpeople").val();
  var back = $("#backpeople").val();
  var checkpeople = [];

  for (let i = 0; i < count; i++) {
    checkpeople.push($("#checkpeople" + i).val());
  }
  console.log(checkpeople);

  pick = pick.split(" ");
  var pickpeople = pick[0];
  back = back.split(" ");
  var backpeople = back[0];
  var check1 = checkpeople.indexOf(pickpeople);
  var check2 = checkpeople.indexOf(backpeople);

  var position = $("#position").val();
  var status = $("#status").val();
  if (status === "good product") status = "良品";
  if (status === "bad product") status = "不良品";

  if (check1 == -1) {
    alert(Lang.get("outboundpageLang.noreceivepeople"));
    $("#pickpeople").css("border-color","red");
    return false;
  }

  if (check2 == -1) {
    alert(Lang.get("outboundpageLang.nobackpeople"));
    $("#backpeople").css("border-color","red");
    return false;
  }

  $.ajax({
    type: "POST",
    url: "backlistsubmit",
    data: {
      list: list,
      advance: advance,
      amount: amount,
      reason: reason,
      backpeople: backpeople,
      pickpeople: pickpeople,
      position: position,
      number: number,
      client: client,
      status: status,
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
    },
    success: function (data) {
      console.log(data);
      var mess = Lang.get("outboundpageLang.outbackok") + list;
      alert(mess);
      //alert("出庫完成，退料單號: " + list);
      window.location.href = "/outbound";
      //window.location.href = "member.newok";
    },
    error: function (err) {
      //noreason
      if (err.status == 420) {
        document.getElementById("reasonerror").style.display = "block";
        document.getElementById("reason" + list).style.borderColor = "red";
      }
      //transaction error
      else if (err.status == 421) {
        console.log(err.status);
      }
    },
  });
});
