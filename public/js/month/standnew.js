
$(document).ready(function () {
  $("input").change(function () {
    var nowpeople = $("#nowpeople").val();
    var nowline = $("#nowline").val();
    var nowclass = $("#nowclass").val();
    var nowuse = $("#nowuse").val();
    var nowchange = $("#nowchange").val();
    var nextpeople = $("#nextpeople").val();
    var nextline = $("#nextline").val();
    var nextclass = $("#nextclass").val();
    var nextuse = $("#nextuse").val();
    var nextchange = $("#nextchange").val();
    var mpq = $("#mpq").val();
    var lt = $("#lt").val();
    var now = (nowpeople * nowclass * nowline * nowuse * nowchange) / mpq;
    var next = (nextpeople * nextclass * nextline * nextuse * nextchange) / mpq;
    var safe = next * lt;
    $('#nowneed').val(now);
    $('#nextneed').val(next);
    $('#safe').val(safe);
  });
});



$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#standnew').on('submit', function (e) {
  e.preventDefault();
  var number = $("#number").val();
  var client = $("#client").val();
  var machine = $("#machine").val();
  var production = $("#production").val();
  var nowpeople = $("#nowpeople").val();
  var nowline = $("#nowline").val();
  var nowclass = $("#nowclass").val();
  var nowuse = $("#nowuse").val();
  var nowchange = $("#nowchange").val();
  var nextpeople = $("#nextpeople").val();
  var nextline = $("#nextline").val();
  var nextclass = $("#nextclass").val();
  var nextuse = $("#nextuse").val();
  var nextchange = $("#nextchange").val();

  $.ajax({
    type: 'POST',
    url: "standnewsubmit",
    data: {
      number: number, client: client, machine: machine, production: production, nowpeople: nowpeople,
      nowline: nowline, nowclass: nowclass, nowuse: nowuse, nowchange: nowchange, nextpeople: nextpeople,
      nextline: nextline, nextclass: nextclass, nextuse: nextuse, nextchange: nextchange
    },
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
        var mess = Lang.get('monthlyPRpageLang.stand')
          + Lang.get('monthlyPRpageLang.new') + Lang.get('monthlyPRpageLang.success');
        alert(mess);
        window.location.href = "/month";
        //window.location.href = "member.newok";
      }
      else {
        var mess = Lang.get('monthlyPRpageLang.repeat');
        alert(mess);
        window.location.href = "standadd";
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});

