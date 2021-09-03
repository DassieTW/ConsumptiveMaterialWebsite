
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$("#usereason").on("change",function(){

    var value = $("#usereason").val();
    if(value === "其他" || value === "other")
    {
        document.getElementById("reason").style.display = "block";
    }
    else{
        document.getElementById("reason").style.display = "none";
    }
});
$('#pick').on('submit', function (e) {
    e.preventDefault();
      var client = $("#client").val();
      var machine = $("#machine").val();
      var production = $("#production").val();
      var line = $("#line").val();
      var usereason = $("#usereason").val();
      if(usereason === "其他"){
          usereason = $('#reason').val();
      }
      var number = $('#number').val();
    $.ajax({
       type:'POST',
       url:"pickadd",
       data:{client:client, machine:machine , production:production , line:line , usereason:usereason , number:number},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true && myObj.passbool === true && myObj.passstock === true ){

            window.location.href = "pickaddok";
            //window.location.href = "member.newok";
          }
          //料號長度不為12
          else if(myObj.boolean === false && myObj.passbool === true && myObj.passstock === false){
            document.getElementById("numbererror").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
            document.getElementById("nostock").style.display = "none";
            document.getElementById("client").style.borderColor = "";
            document.getElementById("numbererror1").style.display = "none";
          }
          //沒有料號
          else if(myObj.boolean === true && myObj.passbool === false && myObj.passstock === false){
            document.getElementById("numbererror1").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
            document.getElementById("nostock").style.display = "none";
            document.getElementById("client").style.borderColor = "";
            document.getElementById("numbererror").style.display = "none";
          }
          //沒有庫存
          else if(myObj.boolean === true && myObj.passbool === true && myObj.passstock === false){
            document.getElementById("nostock").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('client').style.borderColor = "red";
            document.getElementById('number').value='';
            document.getElementById('client').value='';
            document.getElementById("numbererror1").style.display = "none";
            document.getElementById("numbererror").style.display = "none";
          }
       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});
