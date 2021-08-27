
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#pickadd').on('submit', function (e) {
    e.preventDefault();
      var test = 'zero';
      var number = $("#number").val();
      var name = $("#name").val();
      var format = $("#format").val();
      var unit = $("#unit").val();
      var send = $("#send").val();
      var amount = $('#amount').val();
      var remark = $("#remark").val();
      var client = $("#client").val();
      var machine = $("#machine").val();
      var production = $("#production").val();
      var line = $("#line").val();
      var usereason = $('#usereason').val();
      if(remark == ""){remark = test};
    $.ajax({
       type:'POST',
       url:"pickaddsubmit",
       data:{number:number, name:name , format:format , client:client, unit:unit , amount:amount
        , machine:machine, production:production , line:line , usereason:usereason , send:send , remark:remark},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true){
            alert("添加成功，領料單號: " + myObj.message);
            window.location.href = "pickaddsubmitok";
            //window.location.href = "member.newok";
          }
          else{
            window.location.reload();
          }
       },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});
