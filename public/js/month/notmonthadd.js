document.getElementById("error").style.display = "none";
document.getElementById("error").style.color = "red";

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#notmonthadd').on('submit', function (e) {
  e.preventDefault();
  var client = $("#client").val();
  var number = $("#number").val();
  var amount = $("#amount").val();
  var sxb = $("#sxb").val();
  var say = $("#say").val();
  var reason = $("#reason").val();
  var month = $("#month").val();
  $.ajax({
    type: 'POST',
    url: "notmonthadd",
    data: { client: client, number: number, amount: amount, say: say, sxb: sxb, reason: reason, month: month },
    beforeSend: function () {
      // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
      $('body').loadingModal({
        text: 'Loading...',
        animation: 'circle'
      });
    },
    complete: function () {
      $('body').loadingModal('hide');
    },
    success: function (data) {
      console.log(data);
      var myObj = JSON.parse(data);
      console.log(myObj);
      if (myObj.boolean === true) {
        var mess = Lang.get('monthlyPRpageLang.notmonth') + Lang.get('monthlyPRpageLang.add')
          + Lang.get('monthlyPRpageLang.success');
        alert(mess);
        window.location.href = "/month";
        //window.location.href = "member.newok";
      }
      else if (myObj.message !== null) {
        alert(myObj.message);
      }
      else {
        document.getElementById("error").style.display = "block";
        document.getElementById("say").style.borderColor = "red";
        document.getElementById("reason").style.borderColor = "red";
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});
