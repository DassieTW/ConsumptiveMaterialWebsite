document.getElementById("reasonerror").style.display = "none";
document.getElementById("nostock").style.display = "none";
document.getElementById("lessstock").style.display = "none";


//show select 領料單號
$("#list").on("change",function(){
    var value = $("#list").val();
    $('#test').find('tr').not('#require').hide();   　
    var result_style = document.getElementById(value).style;
    result_style.display = 'table-row';
    //document.getElementById("test").style.display = "block";
});


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#picklist').on('submit', function (e) {
    e.preventDefault();
      var list = $("#list").val();
      var advance = $("#advance"+list).html();
      var number = $("#number"+list).html();
      var client = $("#client"+list).html();
      var amount = $("#amount"+list).val();
      var reason = $("#reason"+list).val();
      var send = $("#sendpeople").val();
      send = send.split(' ');
      var sendpeople = send[0];
      var pick = $("#pickpeople").val();
      pick = pick.split(' ');
      var pickpeople = pick[0];
      var bound = $("#bound"+list).val();
      bound = bound.split(' ');
      bound = bound[0];
      bound = bound.split('庫別:');
      bound = bound[1];

    $.ajax({
       type:'POST',
       url:"picklistsubmit",
       data:{list:list, advance:advance , amount:amount , reason:reason , sendpeople:sendpeople
        , pickpeople:pickpeople , bound:bound , number:number , client:client},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true && myObj.passbool === true && myObj.passstock === true ){
            window.location.href = "picklistsubmitok";
            //window.location.href = "member.newok";
          }
          //no reason
          else if(myObj.boolean === true && myObj.passbool === false && myObj.passstock === false){

            document.getElementById("reasonerror").style.display = "block";
            document.getElementById("reason"+list).style.borderColor = "red";
          }
          //庫別沒有庫存
          else if(myObj.boolean === true && myObj.passbool === true && myObj.passstock === false){

            document.getElementById("nostock").style.display = "block";
            document.getElementById("position").style.borderColor = "red";
            $("#nostock #number").html("料號 : " + myObj.number);
            $("#nostock #position").html("庫別 : " + myObj.position);
          }
          //庫別庫存小於實際領用數量
          else if(myObj.boolean === false && myObj.passbool === true && myObj.passstock === true){

            document.getElementById("lessstock").style.display = "block";
            document.getElementById("position").style.borderColor = "red";
            $("#lessstock #position").html("目前庫別 : " + myObj.position + "之庫存小於實際領用數量，無法出庫。");
            $("#lessstock #nowstock").html("現有庫存 : " + myObj.nowstock);
            document.getElementById("amount"+list).style.borderColor = "red";
          }
          else if(myObj.boolean === false && myObj.passbool === false && myObj.passstock === false){

            window.location.reload();
          }
       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});


