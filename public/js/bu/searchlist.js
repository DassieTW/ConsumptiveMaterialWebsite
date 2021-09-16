//only check one
$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
});


var count = $("#count").val();


for(var i = 0 ; i < count ; i++){
    var status = $("#data1"+i).val();
    if(status == "已完成")
    {
        document.getElementById("check"+i).setAttribute('disabled', 'disabled');
    }
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$('#bulist').on('submit', function (e) {
    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var i = $( "input:checked" ).val();

    var list = $("#data0"+i).val();
    checked = $("input[type=checkbox]:checked").length;

    if(!checked) {
        alert(Lang.get('bupagelang.nocheck'));
        return false;
    }
    $.ajax({
        type:'POST',
        url:"delete",
        data:{list:list},
        success:function(data){
         console.log(data);
           var myObj = JSON.parse(data);
           console.log(myObj);
           if(myObj.boolean === true){
            var mess = Lang.get('bupagelang.delete') + ' ' + Lang.get('bupagelang.dblist') +' : '+ myObj.message + ' ' +Lang.get('bupagelang.success');
            alert(mess);

            window.location.href = "/bu";

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
});
