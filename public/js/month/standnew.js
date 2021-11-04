
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
    now = now.toFixed(2);
    next = next.toFixed(2);
    safe = safe.toFixed(2);
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

  // clean up previous input results
  $('.is-invalid').removeClass('is-invalid');
  $(".invalid-feedback").remove();

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
  var email = $("#email").val();
  var jobnumber = $("#jobnumber").val();

  $.ajax({
    type: 'POST',
    url: "standnewsubmit",
    data: {
      number: number, client: client, machine: machine, production: production, nowpeople: nowpeople,
      nowline: nowline, nowclass: nowclass, nowuse: nowuse, nowchange: nowchange, nextpeople: nextpeople,
      nextline: nextline, nextclass: nextclass, nextuse: nextuse, nextchange: nextchange , email:email , jobnumber : jobnumber
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

        var mess = Lang.get('monthlyPRpageLang.stand')
          + Lang.get('monthlyPRpageLang.new') + Lang.get('monthlyPRpageLang.submit') + Lang.get('monthlyPRpageLang.success');
        alert(mess);

        $("#standbody").hide();
        $('#url').append('  URL : ' + '<a>http://127.0.0.1/month/teststand?'+ data.database +'</a>');

    },
    error: function (err) {
        //repeat
        if (err.status == 420) {
            var mess = Lang.get('monthlyPRpageLang.repeat');
            alert(mess);
            return false;

        }
        //transaction error
        else if (err.status == 421) {
            console.log(err.status);
        }
      },
  });
});

