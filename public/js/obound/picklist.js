


//show select 領料單號
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

$('#picklist').on('submit', function (e) {
  e.preventDefault();
  var list = $("#list").val();
  var advance = $("#advance" + list).html();
  var number = $("#number" + list).html();
  var client = $("#client" + list).html();
  var amount = $("#amount" + list).val();
  var reason = $("#reason" + list).val();
  var send = $("#sendpeople").val();
  send = send.split(' ');
  var sendpeople = send[0];
  var pick = $("#pickpeople").val();
  pick = pick.split(' ');
  var pickpeople = pick[0];
  var bound = $("#bound" + list).val();
  if(bound != null)
  {
    bound = bound.split(' ');
    bound = bound[0];
    bound = bound.split('庫別:');
    bound = bound[1];
  }
  else
  {
    document.getElementById("bound" + list).style.borderColor = "red";
    alert(Lang.get('oboundpageLang.enterbound'));
    return false;
  }


  $.ajax({
    type: 'POST',
    url: "picklistsubmit",
    data: {
      list: list, advance: advance, amount: amount, reason: reason, sendpeople: sendpeople
      , pickpeople: pickpeople, bound: bound, number: number, client: client
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
      if (myObj.boolean === true && myObj.passbool === true && myObj.passstock === true) {
        var mess = Lang.get('oboundpageLang.outpickok') + list;
        alert(mess);
        window.location.href = "/obound";
        //window.location.href = "member.newok";
      }
      //no reason
      else if (myObj.boolean === true && myObj.passbool === false && myObj.passstock === false) {

        document.getElementById("reasonerror").style.display = "block";
        document.getElementById("reason" + list).style.borderColor = "red";
        document.getElementById("lessstock").style.display = "none";
        document.getElementById("amount" + list).style.borderColor = "";
        document.getElementById("bound").style.borderColor = "";
      }

      //庫別庫存小於實際領用數量
      else if (myObj.boolean === false && myObj.passbool === true && myObj.passstock === true) {

        document.getElementById("lessstock").style.display = "block";
        document.getElementById("position").style.borderColor = "red";
        var mess = Lang.get('oboundpageLang.nowbound') + ' : ' + myObj.position + '<br>' + Lang.get('oboundpageLang.stockless');
        var mess1 = Lang.get('oboundpageLang.nowstock') + ' : ' + myObj.nowstock;
        $("#lessstock #position").html(mess);
        $("#lessstock #nowstock").html(mess1);
        $("#lessstock #amount").html(Lang.get('outboundpageLang.realpickamount') + ' : ' + amount);
        document.getElementById("amount" + list).style.borderColor = "red";
        document.getElementById("reasonerror").style.display = "none";
      }
      else if (myObj.boolean === false && myObj.passbool === false && myObj.passstock === false) {

        window.location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});


