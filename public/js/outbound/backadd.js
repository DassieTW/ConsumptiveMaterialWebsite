
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#backadd').on('submit', function (e) {
  e.preventDefault();
  var number = $("#number").val();
  var name = $("#name").val();
  var format = $("#format").val();
  var unit = $("#unit").val();
  var send = $("#send").val();
  var amount = $('#amount').val();
  var remark = $("#remark").val();
  var client = $("#client").val();
  var machine = $("#machine").val();
  var production = $("#production").val();
  var line = $("#line").val();
  var backreason = $('#backreason').val();
  $.ajax({
    type: 'POST',
    url: "backaddsubmit",
    data: {
      number: number, name: name, format: format, client: client, unit: unit, amount: amount
      , machine: machine, production: production, line: line, backreason: backreason, send: send, remark: remark
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
        console.log(data.boolean);

        var mess = Lang.get('outboundpageLang.add') + Lang.get('outboundpageLang.success') + ' ， ' +
          Lang.get('outboundpageLang.backlistnum') + ' : ' + data.message;
        alert(mess);
        window.location.href = "/outbound";

    },
    error: function (err) {
        //transaction error
        if (err.status == 420) {
            window.location.reload();
        }
      },
  });
});
