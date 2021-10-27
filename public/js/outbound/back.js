

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$("#backreason").on("change", function () {

    var value = $("#backreason").val();
    if(value === "其他" || value === "other")
    {
        document.getElementById("reason").style.display = "block";
        document.getElementById("reason").required = true;
    }
    else{
        document.getElementById("reason").style.display = "none";
        document.getElementById("reason").required = false;
    }
});
$('#back').on('submit', function (e) {
  e.preventDefault();
  var client = $("#client").val();
  var machine = $("#machine").val();
  var production = $("#production").val();
  var line = $("#line").val();
  var backreason = $("#backreason").val();
  if (backreason === "其他" || backreason === "other") {
    backreason = $('#reason').val();
  }
  var number = $('#number').val();
  $.ajax({
    type: 'POST',
    url: "backadd",
    data: { client: client, machine: machine, production: production, line: line, backreason: backreason, number: number },
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
        window.location.href = "backaddok";
    },
    error: function (err) {
        //料號不存在
        if (err.status == 420) {
            document.getElementById("numbererror1").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value = '';
            document.getElementById("numbererror").style.display = "none";
        }
        //料號長度不為12
        else if (err.status == 421) {
            document.getElementById("numbererror").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value = '';
            document.getElementById("numbererror1").style.display = "none";
        }
      },
  });
});
