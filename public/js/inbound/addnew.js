document.getElementById("showposition").style.display = "none";
document.getElementById("showname").style.display = "none";
document.getElementById("nostock").style.display = "none";
document.getElementById("nostock").style.color = "red";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#addnew').on('submit', function (e) {
    e.preventDefault();
      var client = $("#client").val();
      var number = $("#number").val();
      var name = $("#name").val();
      var format = $("#format").val();
      var unit = $("#unit").val();
      var amount = $("#amount").val();
      var stock = $("#stock").val();
      var inamount = $("#inamount").val();
      var inreason = $("#inreason").val();
      var oldposition = $("#oldposition").val();
      var newposition = $("#newposition").val();
      var inpeo = $("#inpeople").val();
      inpeo = inpeo.split(' ');
      var inpeople = inpeo[0];
      if(stock === null || stock === 0 ) stock = 'zero';
    $.ajax({
       type:'POST',
       url:"addnewsubmit",
       data:{client:client, number:number , name:name , format:format , unit:unit
        , amount:amount , stock:stock , inamount:inamount , inreason:inreason, oldposition:oldposition
        , newposition:newposition , inpeople , inpeople},
       success:function(data){
        console.log(data);
          var myObj = JSON.parse(data);
          console.log(myObj);
          if(myObj.boolean === true && myObj.passbool === true){
            var mess = Lang.get('inboundpageLang.add')+Lang.get('inboundpageLang.success')+' : '+
            Lang.get('inboundpageLang.inlist')+' : '+myObj.message;
            alert(mess);
            //alert("添加成功，入庫單號: " + myObj.message);
            window.location.href = "/inbound";
            //window.location.href = "member.newok";
          }
          //入庫數量大於庫存量
          else if(myObj.boolean === true && myObj.passbool === false){

            document.getElementById("nostock").style.display = "block";
            document.getElementById("inamount").style.borderColor = "red";
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


