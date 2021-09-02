
document.getElementById("numbererror1").style.display = "none";
document.getElementById("numbererror1").style.color = "red";
document.getElementById("reason").style.display = "none";

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$("#backreason").on("change", function () {

  var value = $("#backreason").val();
  if (value === "其他") {
    document.getElementById("reason").style.display = "block";
  }
  else {
    document.getElementById("reason").style.display = "none";
  }
});
$('#back').on('submit', function (e) {
  e.preventDefault();
  var client = $("#client").val();
  var machine = $("#machine").val();
  var production = $("#production").val();
  var line = $("#line").val();
  var backreason = $("#backreason").val();
  if (backreason === "其他") {
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
      console.log(data);
      var myObj = JSON.parse(data);
      console.log(myObj);
      if (myObj.boolean === true && myObj.passbool === true) {

        window.location.href = "backaddok";
        //window.location.href = "member.newok";
      }
      else if (myObj.boolean === false && myObj.passbool === true) {
        document.getElementById("numbererror").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
      }
      else if (myObj.boolean === true && myObj.passbool === false) {
        document.getElementById("numbererror1").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});
