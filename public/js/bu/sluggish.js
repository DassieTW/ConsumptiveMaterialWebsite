//only check one
$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
});



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$('#sluggish').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var i = $( "input:checked" ).val();

    var factory = $("#data0"+i).val();
    var number = $("#data1"+i).val();
    var name = $("#data2"+i).val();
    var format = $("#data3"+i).val();
    var unit = $("#data4"+i).val();
    var oldstock = $("#data6"+i).val();
    var amount = $("#data7"+i).val();
    var receive = $("#data8"+i).val();

    checked = $("input[type=checkbox]:checked").length;

    if(!checked) {
        alert(Lang.get('bupagelang.nocheck1'));
        return false;
    }

    if(amount === '')
    {

        document.getElementById("data7"+i).style.borderColor = "red";
        alert(Lang.get('bupagelang.enteramount'));
        return false;
    }
    if(receive === null)
    {
        document.getElementById("data8"+i).style.borderColor = "red";
        alert(Lang.get('bupagelang.enterfactory'));
        return false;
    }

    if(parseInt(amount)>parseInt(oldstock) || parseInt(amount) <=0)
    {
        alert(Lang.get('bupagelang.amounterr'));
        return false;
    }

    $.ajax({
        type:'POST',
        url:"transsluggish",
        data:{factory : factory , number : number , name : name , format : format ,unit : unit ,
            oldstock:oldstock, amount : amount , receive : receive},
        success:function(data){
         console.log(data);
           var myObj = JSON.parse(data);
           console.log(myObj);
           if(myObj.boolean === true && myObj.passbool === true){
            var mess = Lang.get('bupagelang.dbadd') + ' ' + Lang.get('bupagelang.success') +' ' +
            Lang.get('bupagelang.dblist') + ' : ' + myObj.message;
            alert(mess);

            window.location.href = "/bu";

          }

          else if(myObj.boolean === true && myObj.passbool === false){
            var mess = Lang.get('bupagelang.inventoryerr');

            alert(mess);
            window.location.reload();

          }
          else{
              var mess = myObj.message;
            alert(mess);
            //window.location.reload();
          }
        },
       error : function(jqXHR,textStatus,errorThrown){
        console.warn(jqXHR.responseText);
        alert(errorThrown);
      }
    });
});
