document.getElementById("message").style.display = "none";
document.getElementById("message").style.color = "red";
document.getElementById("message1").style.display = "none";
document.getElementById("message1").style.color = "red";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#new_people').on('submit', function (e) {
    e.preventDefault();
      var number = $("#number").val();
      var name = $("#name").val();
      var department = $("#department").val();
    $.ajax({
       type:'POST',
       url:"new",
       data:{number:number, name:name , department:department},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true && myObj.passbool === true){
            var mess = Lang.get('templateWords.newPInfo')+Lang.get('loginPageLang.success');
            alert(mess);
            //alert('新增人員訊息成功。')
            window.location.href = "/member";
            //window.location.href = "member.newok";
          }
          else if(myObj.boolean === true && myObj.passbool === false){
            document.getElementById("message").style.display = "block";
            document.getElementById('number').style.borderColor = "red";
            document.getElementById('number').value='';
          }
          else if(myObj.boolean === false && myObj.passbool === true){
            document.getElementById("message1").style.display = "block";
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
