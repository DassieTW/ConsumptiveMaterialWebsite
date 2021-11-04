
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });



  $('#uploadnotmonth').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var client = [];
    var number = [];
    var sxb = [];
    var say = [];
    var amount = [];
    var month = [];
    var count = $("#count").val();

    for(let i = 0 ; i < count ; i++)
    {
        client.push($("#data1" + i).val());
        number.push($("#data2" + i).val());
        sxb.push($("#data0" + i).val());
        say.push($("#data5" + i).val() + ' ' + $("#data4" + i).val());
        amount.push($("#data3" + i).val());
        month.push($("#data6" + i).val());
    }

    for(let i = 0 ; i < count ; i++)
    {
        if($("#data6" + i).val() === '是')
        {

            if($("#data5" + i).val() === null || $("#data4" + i).val() === null)
            {
                $("#data5" + i).css("borderColor", "red");
                $("#data4" + i).css("borderColor", "red");
                i++;
                var mess = Lang.get('monthlyPRpageLang.row')+ ' ' + i + ' ' +Lang.get('monthlyPRpageLang.errormonth');
                alert(mess);


                return false;
            }
            else
            {
                continue;
            }
        }
        else{
            continue;
        }

    }
    $.ajax({
      type: 'POST',
      url: "insertuploadnotmonth",
      data: { client: client, number: number, sxb: sxb, say: say ,
        amount :amount , count : count , month : month},
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
        console.log(number);

        var mess = Lang.get('monthlyPRpageLang.total')+(data.message)+Lang.get('monthlyPRpageLang.record')
        + Lang.get('monthlyPRpageLang.notmonth')+Lang.get('monthlyPRpageLang.upload1')
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
          window.location.href='importnotmonth';
      }
      }
    });
  });

