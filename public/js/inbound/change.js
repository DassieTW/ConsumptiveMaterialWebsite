//only check one
$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


    var count = $("#count").val();



    $('#change').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        for(var j = 0 ; j < count ; j++){
            document.getElementById("amount"+j).style.borderColor = "";
            document.getElementById("newposition"+j).style.borderColor = "";
        }

        var i = $( "input:checked" ).val();
        var number = $("#number"+i).val();
        var stock = $("#stock"+i).val();
        var oldposition = $("#oldposition"+i).val();
        var client = $("#client"+i).val();
        var amount = $("#amount"+i).val();
        var newposition = $("#newposition"+i).val();

        checked = $("input[type=checkbox]:checked").length;

        if(!checked) {
            alert(Lang.get('inboundpageLang.nocheck'));
            return false;
        }
        if(amount === '')
        {

            document.getElementById("amount"+i).style.borderColor = "red";
            alert(Lang.get('inboundpageLang.enteramount'));
            return false;
        }
        if(newposition === null)
        {
            document.getElementById("newposition"+i).style.borderColor = "red";
            alert(Lang.get('inboundpageLang.enterloc'));
            return false;
        }

        if(parseInt(amount)>parseInt(stock))
        {
            alert(Lang.get('inboundpageLang.locchangeerr'));
            return false;
        }
        else
        {
            var  mess = Lang.get('inboundpageLang.coming') + ' : ' + oldposition + ' ' + Lang.get('inboundpageLang.transfer') +  ' : ' + number + ' : '
            + amount + ' ' + Lang.get('inboundpageLang.to') + ' '+ newposition + '\n'+Lang.get('inboundpageLang.client')+ ' : ' + client;
            var sure = window.confirm(mess);
        }

        if (sure !== true) {
            return false;
        } else {
        $.ajax({
            type:'POST',
            url:"changesubmit",
            data:{client:client, number:number , oldposition:oldposition , amount:amount ,newposition:newposition , stock:stock},
            success:function(data){
             console.log(data);
               var myObj = JSON.parse(data);
               console.log(myObj);
               if(myObj.boolean === true){
                var mess = Lang.get('inboundpageLang.locationchange') + ' ' + Lang.get('inboundpageLang.success');
                alert(mess);

                window.location.href = "/inbound";

              }

              else if(myObj.boolean === false){

                window.location.reload();
              }
            },
           error : function(jqXHR,textStatus,errorThrown){
            console.warn(jqXHR.responseText);
            alert(errorThrown);
          }
        });
        }
    });

