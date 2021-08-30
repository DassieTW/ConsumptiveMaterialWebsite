
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#backadd').on('submit', function (e) {
    e.preventDefault();
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
      var backreason = $('#backreason').val();

    $.ajax({
       type:'POST',
       url:"backaddsubmit",
       data:{number:number, name:name , format:format , client:client, unit:unit , amount:amount
        , machine:machine, production:production , line:line , backreason:backreason , send:send , remark:remark },
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true){

            var mess = Lang.get('oboundpageLang.add')+Lang.get('oboundpageLang.success')+'，'+
            +Lang.get('oboundpageLang.backlistnum')+' : '+myObj.message;
            alert(mess);
            //alert("添加成功，退料單號: " + myObj.message);
            window.location.href = "/obound";
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
