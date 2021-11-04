

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
      window.location.href = "standnewok";
    },
    error: function (err) {
      //料號長度不為12
      if (err.status == 421) {
        document.getElementById("numbererror").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
        document.getElementById("numbererror1").style.display = "none";

      }
      //料號不存在
      else if (err.status == 420) {
        document.getElementById("numbererror1").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
        document.getElementById("numbererror").style.display = "none";
      }
    }
  });
});
