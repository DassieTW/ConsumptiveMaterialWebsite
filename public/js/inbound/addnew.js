
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#addnew').on('submit', function (e) {
  e.preventDefault();
  var client = $("#client").val();
  var number = $("#number").val();
  var name = $("#name").val();
  var format = $("#format").val();
  var unit = $("#unit").val();
  var amount = $("#amount").val();
  var stock = $("#stock").val();
  var inamount = $("#inamount").val();
  var inreason = $("#inreason").val();
  var oldposition = $("#oldposition").val();
  var newposition = $("#newposition").val();
  var inpeo = $("#inpeople").val();
  inpeo = inpeo.split(' ');
  var inpeople = inpeo[0];
  if (stock === null || stock === 0) stock = 'zero';
  $.ajax({
    type: 'POST',
    url: "addnewsubmit",
    data: {
      client: client, number: number, name: name, format: format, unit: unit
      , amount: amount, stock: stock, inamount: inamount, inreason: inreason, oldposition: oldposition
      , newposition: newposition, inpeople, inpeople
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
        var mess = Lang.get('inboundpageLang.add') + Lang.get('inboundpageLang.success') + ' : ' +
          Lang.get('inboundpageLang.inlist') + ' : ' + data.message;
        alert(mess);
        window.location.href = "/inbound/add";

    },
    error: function (err) {
        if (err.status == 420) {
            document.getElementById("nostock").style.display = "block";
            document.getElementById("inamount").style.borderColor = "red";

            //transaction error
          } else if (err.status == 421) {
            window.location.reload();
          }
    }
  });
});


