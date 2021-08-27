document.getElementById("reasonerror").style.display = "none";


//show select 退料單號
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

$('#backlist').on('submit', function (e) {
    e.preventDefault();
      var list = $("#list").val();
      var advance = $("#advance"+list).html();
      var number = $("#number"+list).html();
      var client = $("#client"+list).html();
      var amount = $("#amount"+list).val();
      var reason = $("#reason"+list).val();
      var pick = $("#pickpeople").val();
      pick = pick.split(' ');
      var pickpeople = pick[0];
      var back = $("#backpeople").val();
      back = back.split(' ');
      var backpeople = back[0];
      var bound = $("#bound").val();
      var status = $("#status").val();
    $.ajax({
       type:'POST',
       url:"backlistsubmit",
       data:{list:list, advance:advance , amount:amount , reason:reason , backpeople:backpeople
        , pickpeople:pickpeople , bound:bound , number:number , client:client , status:status},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true && myObj.passbool === true){
            window.location.href = "backlistsubmitok";
            //window.location.href = "member.newok";
          }
          //no reason
          else if(myObj.boolean === true && myObj.passbool === false){

            document.getElementById("reasonerror").style.display = "block";
            document.getElementById("reason"+list).style.borderColor = "red";
          }
          else if(myObj.boolean === false && myObj.passbool === false){

            window.location.reload();
          }
       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});


