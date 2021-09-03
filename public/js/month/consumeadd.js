document.getElementById("numbererror").style.display = "none";
document.getElementById("numbererror").style.color = "red";
document.getElementById("numbererror1").style.display = "none";
document.getElementById("numbererror1").style.color = "red";

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#consumeadd').on('submit', function (e) {
  e.preventDefault();
  var client = $("#client").val();
  var number = $("#number").val();
  var production = $("#production").val();
  var machine = $("#machine").val();
  $.ajax({
    type: 'POST',
    url: "consumenew",
    data: { client: client, number: number, production: production, machine: machine },
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

        window.location.href = "consumenewok";

      }
      else if (myObj.boolean === false && myObj.passbool === true && myObj.passstock === false) {
        document.getElementById("numbererror").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
      }
      else if (myObj.boolean === true && myObj.passbool === false && myObj.passstock === false) {
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
