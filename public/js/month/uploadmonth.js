
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });



  $('#uploadmonth').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var client = [];
    var machine = [];
    var production = [];
    var nowmps = [];
    var nowday = [];
    var nextmps = [];
    var nextday = [];
    var count = $("#count").val();
    for(let i = 0 ; i < count ; i++)
    {
        client.push($("#data0" + i).val());
        machine.push($("#data1" + i).val());
        production.push($("#data2" + i).val());
        nowmps.push($("#data3" + i).val());
        nowday.push($("#data4" + i).val());
        nextmps.push($("#data5" + i).val());
        nextday.push($("#data6" + i).val());
    }

    $.ajax({
      type: 'POST',
      url: "insertuploadmonth",
      data: { client: client, machine: machine, production: production, nowmps: nowmps ,
        nowday :nowday , nextmps : nextmps , nextday : nextday , count:count},
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
        var mess = Lang.get('monthlyPRpageLang.total')+(data.message)+Lang.get('monthlyPRpageLang.record')
        + Lang.get('monthlyPRpageLang.month')+Lang.get('monthlyPRpageLang.upload1')
        +Lang.get('monthlyPRpageLang.success');
        alert(mess);
        window.location.href='/month';

  },
  error: function (err) {
      console.log(err);
      //repeat
      if (err.status == 420) {
          var mess = Lang.get('monthlyPRpageLang.row') + err.responseJSON.message + Lang.get('monthlyPRpageLang.repeat');
          alert(mess);
          window.location.href='importmonth';
      }
      }
    });
  });

