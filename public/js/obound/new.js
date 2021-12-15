


$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#newmaterial').on('submit', function (e) {
  e.preventDefault();
  var number = $("#number").val();
  var name = $("#name").val();
  var format = $("#format").val();
  $.ajax({
    type: 'POST',
    url: "new",
    data: { number: number, name: name, format: format },
    beforeSend: function () {
      // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
      $('body').loadingModal({
        text: 'Loading...',
        animation: 'circle'
      });
    },
    complete: function () {
      $('body').loadingModal('hide');
      $('body').loadingModal('destroy');
    },
    success: function (data) {
      console.log(data);
      var myObj = JSON.parse(data);
      console.log(myObj);
      if (myObj.boolean === true && myObj.passbool === true) {
        var mess = Lang.get('oboundpageLang.newMats') + Lang.get('oboundpageLang.success');
        alert(mess);
        window.location.href = "/obound";
      }
      else if (myObj.boolean === false && myObj.passbool === true) {
        document.getElementById("numbererror").style.display = "block";
        document.getElementById('number').style.borderColor = "red";
        document.getElementById('number').value = '';
      }
      else if (myObj.boolean === false && myObj.passbool === false) {
        window.location.reload();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});
