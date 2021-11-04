

var count = $("#count").val();
for(var i = 0 ; i < count ; i++){
    var nowmps = $("#data8"+i).val();
    var nowday = $("#data9"+i).val();
    var amount = $("#data1"+i).val();
    var nextmps = $("#data10"+i).val();
    var nextday = $("#data11"+i).val();
    var lt = $("#data12"+i).val();
    var nowneed = (nowmps * amount ) / nowday;
    var nextneed = (nextmps * amount ) / nextday;
    var safe = nextneed * lt ;
    nowneed = nowneed.toFixed(7);
    nextneed = nextneed.toFixed(7);
    safe = safe.toFixed(7);
    $('#data2'+i).val(nowneed);
    $('#data3'+i).val(nextneed);
    $('#data4'+i).val(safe);
}
$(document).ready(function(){
    $("input").change(function(){

        for(var i = 0 ; i < count ; i++){
            var nowmps = $("#data8"+i).val();
            var nowday = $("#data9"+i).val();
            var amount = $("#data1"+i).val();
            var nextmps = $("#data10"+i).val();
            var nextday = $("#data11"+i).val();
            var lt = $("#data12"+i).val();
            var nowneed = (nowmps * amount ) / nowday;
            var nextneed = (nextmps * amount ) / nextday;
            var safe = nextneed * lt ;
            nowneed = nowneed.toFixed(7);
            nextneed = nextneed.toFixed(7);
            safe = safe.toFixed(7);
            $('#data2'+i).val(nowneed);
            $('#data3'+i).val(nextneed);
            $('#data4'+i).val(safe);
        }

    });
  });



$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });



  $('#uploadconsume').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();


    var client = [];
    var number = [];
    var production = [];
    var machine = [];
    var amount = [];
    var jobnumber = $("#jobnumber").val();
    var email = $("#email").val();

    var count = $("#count").val();

    for(let i = 0 ; i < count ; i++)
    {
        client.push($("#data5" + i).val());
        machine.push($("#data6" + i).val());
        production.push($("#data7" + i).val());
        number.push($("#data0" + i).val());
        amount.push($("#data1" + i).val());

    }

    $.ajax({
      type: 'POST',
      url: "insertuploadconsume",
      data: { client: client, number: number, production: production, machine: machine ,
        amount :amount ,jobnumber:jobnumber , email:email , count : count},
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
        + Lang.get('monthlyPRpageLang.isn')+Lang.get('monthlyPRpageLang.consume')
        +Lang.get('monthlyPRpageLang.submit')+Lang.get('monthlyPRpageLang.success');
    alert(mess);
    $("#consumebody").hide();
    $('#url').append('  URL : ' + '<a>http://127.0.0.1/month/testconsume?'+ data.database +'</a>');

  },
  error: function (err) {
      console.log(err);
      //repeat
      if (err.status == 420) {
          var mess = Lang.get('monthlyPRpageLang.row') + err.responseJSON.message + Lang.get('monthlyPRpageLang.repeat');
          alert(mess);
          window.location.href='uploadconsume';
      }
      }
    });
  });

