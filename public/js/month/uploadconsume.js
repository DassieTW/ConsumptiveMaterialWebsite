

var count = $("#count").val();
for(var i = 0 ; i < count ; i++){
    var nowmps = $("#data8"+i).val();
    var nowday = $("#data9"+i).val();
    var amount = $("#data1"+i).val();
    var nextmps = $("#data10"+i).val();
    var nextday = $("#data11"+i).val();
    var lt = $("#data12"+i).val();
    var nowneed = (nowmps * amount ) / nowday;
    var nextneed = (nextmps * amount ) / nextday;
    var safe = nextneed * lt ;
    nowneed.toFixed(2);
    nextneed.toFixed(2);
    safe.toFixed(2);
    $('#data2'+i).val(nowneed);
    $('#data3'+i).val(nextneed);
    $('#data4'+i).val(safe);
}
$(document).ready(function(){
    $("input").change(function(){

        for(var i = 0 ; i < count ; i++){
            var nowmps = $("#data8"+i).val();
            var nowday = $("#data9"+i).val();
            var amount = $("#data1"+i).val();
            var nextmps = $("#data10"+i).val();
            var nextday = $("#data11"+i).val();
            var lt = $("#data12"+i).val();
            var nowneed = (nowmps * amount ) / nowday;
            var nextneed = (nextmps * amount ) / nextday;
            var safe = nextneed * lt ;
            nowneed.toFixed(2);
            nextneed.toFixed(2);
            safe.toFixed(2);
            $('#data2'+i).val(nowneed);
            $('#data3'+i).val(nextneed);
            $('#data4'+i).val(safe);
        }

    });
  });
