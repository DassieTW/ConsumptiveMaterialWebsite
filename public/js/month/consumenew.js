$(document).ready(function () {
  $("input").change(function () {
    var nowmps = $("#nowmps").val();
    var amount = $("#amount").val();
    var nowday = $("#nowday").val();
    var nextmps = $("#nextmps").val();
    var nextday = $("#nextday").val();
    var lt = $("#lt").val();
    var nowneed = (nowmps * amount) / nowday;
    var nextneed = (nextmps * amount) / nextday;
    var safe = nextneed * lt;
    $('#nowneed').val(nowneed);
    $('#nextneed').val(nextneed);
    $('#safe').val(safe);
  });
});

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#consumenew').on('submit', function (e) {
  e.preventDefault();
  var number = $("#number").val();
  var client = $("#client").val();
  var machine = $("#machine").val();
  var production = $("#production").val();
  var amount = $("#amount").val();

  $.ajax({
    type: 'POST',
    url: "consumenewsubmit",
    data: { number: number, client: client, machine: machine, production: production, amount: amount },
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
        var mess = Lang.get('monthlyPRpageLang.isn') + Lang.get('monthlyPRpageLang.consume')
          + Lang.get('monthlyPRpageLang.new') + Lang.get('monthlyPRpageLang.success');
        alert(mess);

        window.location.href = "/month";
        //window.location.href = "member.newok";
      }
      else {
        var mess = Lang.get('monthlyPRpageLang.repeat');
        alert(mess);
        window.location.href = "consumeadd";
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});
