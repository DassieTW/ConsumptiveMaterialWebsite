document.getElementById("numbererror").style.display = "none";
document.getElementById("numbererror").style.color = "red";
document.getElementById("numbererror1").style.display = "none";
document.getElementById("numbererror1").style.color = "red";
document.getElementById("safeerror").style.display = "none";
document.getElementById("safeerror").style.color = "red";
$(document).ready(function(){
    $("select").change(function(){
    var checkedValue = $("#month").val();
        if(checkedValue === '是' )
        {
            $('#safe').attr('disabled', true);
        }
        else{
            $('#safe').attr('disabled', false);
        }
    })
});
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
        var price = $("#price").val();
        var unit = $("#unit").val();
        var money = $("#money").val();
        var mpq = $("#mpq").val();
        var moq = $("#moq").val();
        var lt = $("#lt").val();
        var gradea = $("#gradea").val();
        var belong = $("#belong").val();
        var month = $("#month").val();
        var send = $("#send").val();
        var safe = $("#safe").val();
    $.ajax({
       type:'POST',
       url:"new",
       data:{number:number, name:name , format:format , price:price, unit:unit , money:money
        , mpq:mpq, moq:moq , lt:lt , gradea:gradea, belong:belong
        , month:month, send:send , safe:safe},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          //if(safe == null) safe = 'zero';
          if(myObj.newerror[0] === true){
            document.getElementById("numbererror").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
          }
          else if(myObj.newerror[1] === true){
            document.getElementById("numbererror1").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
          }
          else if(myObj.newerror[2] === true){
            document.getElementById("safeerror").style.display = "block";
            document.getElementById('safe').style.borderColor = "red";
            document.getElementById('safe').value='';
          }
          else if(myObj.boolean === true){
            var mess = Lang.get('oboundpageLang.newMats')+Lang.get('oboundpageLang.success');
            alert(mess);
            //alert('New Material successfully');
            window.location.href = "/basic";
          }
       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});
