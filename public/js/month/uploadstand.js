var count = $("#count").val();
for(var i = 0 ; i < count ; i++){
    var nowpeople = $("#data3"+i).val();
    var nowline = $("#data4"+i).val();
    var nowclass = $("#data5"+i).val();
    var nowuse = $("#data6"+i).val();
    var nowchange = $("#data7"+i).val();
    var nextpeople = $("#data9"+i).val();
    var nextline = $("#data10"+i).val();
    var nextclass = $("#data11"+i).val();
    var nextuse = $("#data12"+i).val();
    var nextchange = $("#data13"+i).val();
    var mpq = $("#data1"+i).val();
    var lt = $("#data2"+i).val();
    var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
    var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
    var safe = next * lt ;
    now = now.toFixed(7);
    next = next.toFixed(7);
    safe = safe.toFixed(7);
    $('#data8'+i).val(now);
    $('#data14'+i).val(next);
    $('#data15'+i).val(safe);

}
$(document).ready(function(){
    $("input").change(function(){

        for(var i = 0 ; i < count ; i++){
            var nowpeople = $("#data3"+i).val();
            var nowline = $("#data4"+i).val();
            var nowclass = $("#data5"+i).val();
            var nowuse = $("#data6"+i).val();
            var nowchange = $("#data7"+i).val();
            var nextpeople = $("#data9"+i).val();
            var nextline = $("#data10"+i).val();
            var nextclass = $("#data11"+i).val();
            var nextuse = $("#data12"+i).val();
            var nextchange = $("#data13"+i).val();
            var mpq = $("#data1"+i).val();
            var lt = $("#data2"+i).val();
            var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
            var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
            var safe = next * lt ;
            now = now.toFixed(7);
            next = next.toFixed(7);
            safe = safe.toFixed(7);
            $('#data8'+i).val(now);
            $('#data14'+i).val(next);
            $('#data15'+i).val(safe);
        }

    });
  });

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });



  $('#uploadstand').on('submit', function (e) {
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
    var jobnumber = $("#jobnumber").val();
    var email = $("#email").val();

    var count = $("#count").val();

    for(let i = 0 ; i < count ; i++)
    {
        client.push($("#data16" + i).val());
        machine.push($("#data17" + i).val());
        production.push($("#data18" + i).val());
        number.push($("#data0" + i).val());
        nowpeople.push($("#data3"+i).val());
        nowline.push($("#data4"+i).val());
        nowclass.push($("#data5"+i).val());
        nowuse.push($("#data6"+i).val());
        nowchange.push($("#data7"+i).val());
        nextpeople.push($("#data9"+i).val());
        nextline.push($("#data10"+i).val());
        nextclass.push($("#data11"+i).val());
        nextuse.push($("#data12"+i).val());
        nextchange.push($("#data13"+i).val());

    }

    $.ajax({
      type: 'POST',
      url: "insertuploadstand",
      data: { client: client, number: number, production: production, machine: machine ,
        nowpeople :nowpeople , nowline : nowline , nowclass : nowclass , nowuse : nowuse , nowchange : nowchange ,
        nextpeople :nextpeople , nextline : nextline , nextclass : nextclass , nextuse : nextuse , nextchange : nextchange ,
        jobnumber:jobnumber , email:email , count : count},
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
        + Lang.get('monthlyPRpageLang.isn')+Lang.get('monthlyPRpageLang.stand')
        +Lang.get('monthlyPRpageLang.submit')+Lang.get('monthlyPRpageLang.success');
        alert(mess);
        console.log(data.message);
        $("#standbody").hide();
        $('#url').append('  URL : ' + '<a>http://127.0.0.1/month/teststand?'+ data.database +'</a>');

    },
    error: function (err) {
        console.log(err);
        //repeat
        if (err.status == 420) {
            var mess = Lang.get('monthlyPRpageLang.row') + err.responseJSON.message + Lang.get('monthlyPRpageLang.repeat');
            alert(mess);
            window.location.href='uploadstand';
        }
        }
    });
  });
