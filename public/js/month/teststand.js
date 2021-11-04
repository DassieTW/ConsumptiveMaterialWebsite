

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
    var nowpeople = [];
    var nowline = [];
    var nowclass = [];
    var nowuse = [];
    var nowchange = [];
    var nextpeople = [];
    var nextline = [];
    var nextclass = [];
    var nextuse = [];
    var nextchange = [];
    var comnowpeople = [];
    var comnowline = [];
    var comnowclass = [];
    var comnowuse = [];
    var comnowchange = [];
    var comnextpeople = [];
    var comnextline = [];
    var comnextclass = [];
    var comnextuse = [];
    var comnextchange = [];
    var check = [];
    var jobnumber = $("#jobnumber").val();
    var email = $("#email").val();
    var count = $("#count").val();
    for(let i = 0 ; i < count ; i++)
    {
        client.push($("#client" + i).val());
        number.push($("#number" + i).val());
        production.push($("#production" + i).val());
        machine.push($("#machine" + i).val());
        nowpeople.push($("#nowpeople" + i).val());
        nowline.push($("#nowline" + i).val());
        nowclass.push($("#nowclass" + i).val());
        nowuse.push($("#nowuse" + i).val());
        nowchange.push($("#nowchange" + i).val());
        nextpeople.push($("#nextpeople" + i).val());
        nextline.push($("#nextline" + i).val());
        nextclass.push($("#nextclass" + i).val());
        nextuse.push($("#nextuse" + i).val());
        nextchange.push($("#nextchange" + i).val());
        comnowpeople.push($("#comnowpeople" + i).val());
        comnowline.push($("#comnowline" + i).val());
        comnowclass.push($("#comnowclass" + i).val());
        comnowuse.push($("#comnowuse" + i).val());
        comnowchange.push($("#comnowchange" + i).val());
        comnextpeople.push($("#comnextpeople" + i).val());
        comnextline.push($("#comnextline" + i).val());
        comnextclass.push($("#comnextclass" + i).val());
        comnextuse.push($("#comnextuse" + i).val());
        comnextchange.push($("#comnextchange" + i).val());

    }

    for(let i = 0 ; i < count ; i++)
    {
        if(parseInt(nowpeople[i]) != parseInt(comnowpeople[i]) || parseInt(nowline[i]) != parseInt(comnowline[i]) ||
        parseInt(nowclass[i]) != parseInt(comnowclass[i]) || parseInt(nowuse[i]) != parseInt(comnowuse[i]) ||
        parseInt(nowchange[i]) != parseInt(comnowchange[i]) || parseInt(nextpeople[i]) != parseInt(comnextpeople[i]) ||
        parseInt(nextline[i]) != parseInt(comnowline[i]) || parseInt(nextclass[i]) != parseInt(comnextclass[i]) ||
        parseInt(nextuse[i]) != parseInt(comnextuse[i]) || parseInt(nextchange[i]) != parseInt(comnextchange[i]))
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
      url: "teststandsubmit",
      data: { client: client, number: number, production: production, machine: machine ,
        nowpeople :nowpeople , nowline : nowline , nowclass : nowclass , nowuse : nowuse , nowchange : nowchange ,
        nextpeople :nextpeople , nextline : nextline , nextclass : nextclass , nextuse : nextuse , nextchange : nextchange ,
        jobnumber:jobnumber , email:email , count : count , check : check},
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
        var mess = Lang.get('monthlyPRpageLang.total')+(data.message)+Lang.get('monthlyPRpageLang.record')
            +Lang.get('monthlyPRpageLang.success');
        alert(mess);
        window.location.href = "/month";

      },
      error: function (err) {
        console.log(err.status);
        var mess = err.responseJSON.message;
        alert(mess);
      }
    });
  });
