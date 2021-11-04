


//show select 退料單號
$("#list").on("change", function () {
  var value = $("#list").val();
  $('#test').find('tr').not('#require').hide();
  var result_style = document.getElementById(value).style;
  result_style.display = 'table-row';
  //document.getElementById("test").style.display = "block";
});


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
var otherback;
var otherpick;

$("#backpeople").on("change", function () {

    var value = $("#backpeople").val();
    if (value === "其他" || value === "other") {
        document.getElementById("inputbackpeople").style.display = "block";
        document.getElementById("inputbackpeople").required = true;
        otherback = true;
    }
    else {
        document.getElementById("inputbackpeople").style.display = "none";
        document.getElementById("inputbackpeople").required = false;
        otherback = false;
    }
});

$("#pickpeople").on("change", function () {

    var value = $("#pickpeople").val();
    if (value === "其他" || value === "other") {
        document.getElementById("inputpickpeople").style.display = "block";
        document.getElementById("inputpickpeople").required = true;
        otherpick = true;
    }
    else {
        document.getElementById("inputpickpeople").style.display = "none";
        document.getElementById("inputpickpeople").required = false;
        otherpick = false;
    }
});

$('#backlist').on('submit', function (e) {
    e.preventDefault();
      var count = $("#count").val();
      var list = $("#list").val();
      var advance = $("#advance"+list).html();
      var number = $("#number"+list).html();
      var client = $("#client"+list).html();
      var amount = $("#amount"+list).val();
      var reason = $("#reason"+list).val();
      var checkpeople = [];

      for(let i = 0 ; i < count ; i++)
      {
        checkpeople.push($("#checkpeople" + i).val());
      }
      console.log(checkpeople);
      var inputbackpeople = $("#inputbackpeople").val();
      var check1 = checkpeople.indexOf(inputbackpeople);
      var inputpickpeople = $("#inputpickpeople").val();
      var check2 = checkpeople.indexOf(inputpickpeople);

      var pick = $("#pickpeople").val();
      pick = pick.split(' ');
      var pickpeople = pick[0];
      var back = $("#backpeople").val();
      back = back.split(' ');
      var backpeople = back[0];
      var position = $("#position").val();
      var status = $("#status").val();
      if(status === "good product") status = '良品';
      if(status === "bad product") status = '不良品';
      if(otherback)
      {
        if(check1 == -1)
        {
            alert(Lang.get('outboundpageLang.nobackpeople'));
            return false;
        }
        else
        {
            backpeople = $("#inputbackpeople").val();
        }
      }
      if(otherpick)
      {
        if(check2 == -1)
        {
            alert(Lang.get('outboundpageLang.noreceivepeople'));
            return false;
        }
        else
        {
            pickpeople = $("#inputpickpeople").val();
        }
      }

    $.ajax({
       type:'POST',
       url:"backlistsubmit",
       data:{list:list, advance:advance , amount:amount , reason:reason , backpeople:backpeople
        , pickpeople:pickpeople , position:position , number:number , client:client , status:status},
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
            var mess = Lang.get('outboundpageLang.outbackok')+list;
            alert(mess);
            //alert("出庫完成，退料單號: " + list);
            window.location.href = "/outbound";
            //window.location.href = "member.newok";
          },
        error: function (err) {
            //noreason
            if (err.status == 420) {
                document.getElementById("reasonerror").style.display = "block";
                document.getElementById("reason" + list).style.borderColor = "red";
            }
            //transaction error
            else if (err.status == 421) {
                console.log(err.status);
            }
          },

  });
});


