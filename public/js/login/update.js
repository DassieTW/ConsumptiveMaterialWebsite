$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#update').on('submit', function (e) {
    e.preventDefault();
      var number = $("#number").val();
      var name = $("#name").val();
      var department = $("#department").val();
    $.ajax({
       type:'POST',
       url:"update",
       data:{number:number , name:name , department:department},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true){

            window.location.href = "updateok";
            //window.location.href = "member.newok";
          }
          else{
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
