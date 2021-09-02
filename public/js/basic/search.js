document.getElementById("message").style.display = "none";
document.getElementById("message").style.color = "red";

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$('#searchmaterial').on('submit', function (e) {
  e.preventDefault();
  var number = $("#number").val();
  $.ajax({
    type: 'POST',
    url: "searchmaterial",
    data: { number: number },
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
      if (myObj.boolean === true) {

        window.location.href = "searchmaterialok";
        //window.location.href = "member.newok";
      }
      else {
        document.getElementById("message").style.display = "block";
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

$('#searchposition').on('submit', function (e) {
  e.preventDefault();
  var position = $("#position").val();
  $.ajax({
    type: 'POST',
    url: "searchposition",
    data: { position: position },
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
      if (myObj.boolean === true) {

        window.location.href = "searchpositionok";
        //window.location.href = "member.newok";
      }
      else {
        document.getElementById("message").style.display = "block";
        document.getElementById('position').style.borderColor = "red";
        document.getElementById('position').value = '';
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.warn(jqXHR.responseText);
      alert(errorThrown);
    }
  });
});
