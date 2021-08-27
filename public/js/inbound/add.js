document.getElementById("numbererror").style.display = "none";
document.getElementById("numbererror").style.color = "red";
document.getElementById("numbererror1").style.display = "none";
document.getElementById("numbererror1").style.color = "red";
document.getElementById("reason").style.display = "none";
document.getElementById("noamount").style.color = "red";
document.getElementById("noamount").style.display = "none";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
$("#inreason").on("change",function(){

    var value = $("#inreason").val();
    if(value === "其他")
    {
        document.getElementById("reason").style.display = "block";
    }
    else{
        document.getElementById("reason").style.display = "none";
    }
});



$('#add').on('submit', function (e) {
    e.preventDefault();
      var client = $("#client").val();
      var inreason = $("#inreason").val();
      var number = $("#number").val();
      if(inreason === "其他"){
        inreason = $('#reason').val();
      }
      var submit = buttonIndex;
      if(number === null) number = 'zero';
    $.ajax({
       type:'POST',
       url:"addnew",
       data:{client:client, inreason:inreason , number:number , submit:submit},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true && myObj.passbool === true && myObj.passstock === true ){

            window.location.href = "addnewok";
            //window.location.href = "member.newok";
          }
          //不等於12
          else if(myObj.boolean === true && myObj.passbool === false && myObj.passstock === false){
            document.getElementById("numbererror").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
          }
          //無料號
          else if(myObj.boolean === true && myObj.passbool === true && myObj.passstock === false){
            document.getElementById("numbererror1").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
          }
          //在途量為0
          else if(myObj.boolean === true && myObj.passbool === false && myObj.passstock === true){
            document.getElementById("noamount").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('client').style.borderColor = "red";
            document.getElementById('inreason').style.borderColor = "red";
            document.getElementById('inreason').value='';
            document.getElementById('number').value='';
            document.getElementById('client').value='';
          }

          //添加By客戶別
          else if(myObj.boolean === false && myObj.passbool === true && myObj.passstock === false){
            window.location.href = "/inbound/addclient";
          }
       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});
