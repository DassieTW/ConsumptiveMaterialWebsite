$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
document.getElementById("message").style.display = "none";
document.getElementById("message").style.color = "red";

$('#update').on('submit', function (e) {
    e.preventDefault();
      var number = $("#number").val();
      var name = $("#name").val();
      var format = $("#format").val();
      var price = $("#price").val();
      var unit = $("#unit").val();
      var money = $("#money").val();
      var mpq = $("#mpq").val();
      var moq = $("#moq").val();
      var lt = $("#lt").val();
      var gradea = $("#gradea").val();
      var gp = $("#gp").val();
      var belong = $("#belong").val();
      var month = $("#month").val();
      var send = $("#send").val();
      var safe = $("#safe").val();
    $.ajax({
       type:'POST',
       url:"modify",
       data:{number:number, name:name , format:format , price:price, unit:unit , money:money
       , mpq:mpq, moq:moq , lt:lt , gradea:gradea, gp:gp , belong:belong
       , month:month, send:send , safe:safe},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true){

            window.location.href = "modifyok";
            //window.location.href = "member.newok";
          }
          else{
            document.getElementById("message").style.display = "block";
            document.getElementById('safe').style.borderColor = "red";
            document.getElementById('safe').value='';
          }
       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});
