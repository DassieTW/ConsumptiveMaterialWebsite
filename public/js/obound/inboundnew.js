
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#inboundnew').on('submit', function (e) {
  e.preventDefault();
  var client = $("#client").val();
  var number = $("#number").val();
  var name = $("#name").val();
  var format = $("#format").val();
  var amount = $("#amount").val();
  var remark = $("#remark").val();
  var inreason = $("#inreason").val();
  var bound = $("#bound").val();
  var inpeo = $("#inpeople").val();
  inpeo = inpeo.split(' ');
  var inpeople = inpeo[0];
  console.log(remark);
  //if(remark === null || remark === '' ) remark = 'z';
  $.ajax({
    type: 'POST',
    url: "inboundnewsubmit",
    data: {
      client: client, number: number, name: name, format: format, amount: amount
      , remark: remark, inreason: inreason, bound: bound, inpeople: inpeople
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
      if (myObj.boolean === true && myObj.passbool === true) {

        var mess = Lang.get('oboundpageLang.add') + Lang.get('oboundpageLang.success') + '，' +' '
          +Lang.get('oboundpageLang.inlist') + ' : ' + myObj.message;
        alert(mess);
        //alert("添加成功，入庫單號: " + myObj.message);
        window.location.href = "/obound";
        //window.location.href = "member.newok";
      }
      //入庫數量<0
      else if (myObj.boolean === true && myObj.passbool === false) {

        document.getElementById("amounterror").style.display = "block";
        document.getElementById("amount").style.borderColor = "red";
      }

      else if (myObj.boolean === false && myObj.passbool === false) {

        alert(myObj.message);
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});


