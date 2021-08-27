var count = $("#count").val();
for(var i = 0 ; i < count ; i++){
    var nowpeople = $("#data3"+i).val();
    var nowline = $("#data4"+i).val();
    var nowclass = $("#data5"+i).val();
    var nowuse = $("#data6"+i).val();
    var nowchange = $("#data7"+i).val();
    var nextpeople = $("#data9"+i).val();
    var nextline = $("#data10"+i).val();
    var nextclass = $("#data11"+i).val();
    var nextuse = $("#data12"+i).val();
    var nextchange = $("#data13"+i).val();
    var mpq = $("#data1"+i).val();
    var lt = $("#data2"+i).val();
    var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
    var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
    var safe = next * lt ;
    $('#data8'+i).val(now);
    $('#data14'+i).val(next);
    $('#data15'+i).val(safe);

}
$(document).ready(function(){
    $("input").change(function(){

        for(var i = 0 ; i < count ; i++){
            var nowpeople = $("#data3"+i).val();
            var nowline = $("#data4"+i).val();
            var nowclass = $("#data5"+i).val();
            var nowuse = $("#data6"+i).val();
            var nowchange = $("#data7"+i).val();
            var nextpeople = $("#data9"+i).val();
            var nextline = $("#data10"+i).val();
            var nextclass = $("#data11"+i).val();
            var nextuse = $("#data12"+i).val();
            var nextchange = $("#data13"+i).val();
            var mpq = $("#data1"+i).val();
            var lt = $("#data2"+i).val();
            var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
            var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
            var safe = next * lt ;
            $('#data8'+i).val(now);
            $('#data14'+i).val(next);
            $('#data15'+i).val(safe);
        }

    });
  });
