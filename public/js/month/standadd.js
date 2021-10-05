

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#standadd').on('submit', function (e) {
  e.preventDefault();

  // clean up previous input results
  $('.is-invalid').removeClass('is-invalid');
  $(".invalid-feedback").remove();

  var client = $("#client").val();
  var number = $("#number").val();
  var production = $("#production").val();
  var machine = $("#machine").val();
  $.ajax({
    type: 'POST',
    url: "standnew",
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

        window.location.href = "standnewok";

      }
      else if (myObj.boolean === false && myObj.passbool === true && myObj.passstock === false) {
        document.getElementById("numbererror").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
        document.getElementById("numbererror1").style.display = "none";
      }
      else if (myObj.boolean === true && myObj.passbool === false && myObj.passstock === false) {
        document.getElementById("numbererror1").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
        document.getElementById("numbererror").style.display = "none";
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});
