document.getElementById("numbererror").style.display = "none";
document.getElementById("numbererror").style.color = "red";


$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#test').on('submit', function (e) {
    e.preventDefault();
      var client = $("#client").val();
      var inreason = $("#inreason").val();
      var number = $("#number").val();

    $.ajax({
       type:'POST',
       url:"inboundnew",
       data:{client:client, inreason:inreason , number:number},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true){
            window.location.href = "inboundnewok";
            //window.location.href = "member.newok";
          }
          //無料號
          else if(myObj.boolean === false){
            document.getElementById("numbererror").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
          }

       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});
