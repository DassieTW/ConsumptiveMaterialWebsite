

$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });



  $('#test').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();


    var client = [];
    var number = [];
    var production = [];
    var machine = [];
    var amount = [];
    var check = [];
    var compare = [];
    var jobnumber = $("#jobnumber").val();
    var email = $("#email").val();
    var count = $("#count").val();
    for(let i = 0 ; i < count ; i++)
    {
        client.push($("#client" + i).val());
        number.push($("#number" + i).val());
        production.push($("#production" + i).val());
        machine.push($("#machine" + i).val());
        amount.push($("#amount" + i).val());
        compare.push($("#compare" + i).val());
    }

    for(let i = 0 ; i < count ; i++)
    {
        if(parseFloat(amount[i]) !== parseFloat(compare[i]))
        {
            check[i] = 1;
        }
        else
        {
            check[i] = 0;
        }
    }

    $.ajax({
      type: 'POST',
      url: "testconsume",
      data: { client: client, number: number, production: production, machine: machine ,
        amount :amount  , jobnumber:jobnumber , email:email , count : count , check : check},
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
               +Lang.get('monthlyPRpageLang.success');
            alert(mess);
            window.location.href = "/month";

        },
        error: function (err) {
            //transaction error
            console.log(err.status);
            var mess = err.responseJSON.message;
            alert(mess);
        },

    });
  });
