


$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });



  $('#uploadpeople').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var count = $("#count").val();
    var number = [];
    var name = [];
    var department = [];

    for(let i = 0 ; i < count ; i++)
    {
        number.push($("#data0" + i).val());
        name.push($("#data1" + i).val());
        department.push($("#data2" + i).val());

    }

    $.ajax({
      type: 'POST',
      url: "insertuploadpeople",
      data: { number: number, name: name, department: department , count : count},
      beforeSend: function () {
        // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
        $('body').loadingModal({
          text: 'Loading...',
          animation: 'circle'
        });
      },
      complete: function () {
        $('body').loadingModal('hide');
        $('body').loadingModal('destroy');
      },
      success: function (data) {
        console.log(number);
        var mess = Lang.get('loginPageLang.total')+(data.message)+Lang.get('loginPageLang.record')
        + Lang.get('loginPageLang.pinf')+Lang.get('loginPageLang.upload1')+Lang.get('loginPageLang.success');
        alert(mess);
        window.location.href='/member';
  },
  error: function (err) {
      console.log(err);
      //repeat
      if (err.status == 420) {
          var mess = Lang.get('loginPageLang.row') + ' ' +err.responseJSON.message + ' ' +Lang.get('loginPageLang.jobrepeat');
          alert(mess);
          window.location.href='new';
      }
      //不為9
      if (err.status == 421) {
        var mess = Lang.get('loginPageLang.row') + ' ' +err.responseJSON.message + ' ' +Lang.get('loginPageLang.joblength');
        alert(mess);
        window.location.href='new';
        }
      }
    });
  });

