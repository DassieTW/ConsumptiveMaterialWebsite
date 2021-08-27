var count = $("#count").val();
for(var i = 0 ; i < count ; i++){
    var nowpeople = $("#nowpeople"+i).val();
    var nowline = $("#nowline"+i).val();
    var nowclass = $("#nowclass"+i).val();
    var nowuse = $("#nowuse"+i).val();
    var nowchange = $("#nowchange"+i).val();
    var nextpeople = $("#nextpeople"+i).val();
    var nextline = $("#nextline"+i).val();
    var nextclass = $("#nextclass"+i).val();
    var nextuse = $("#nextuse"+i).val();
    var nextchange = $("#nextchange"+i).val();
    var mpq = $("#mpq"+i).val();
    var lt = $("#lt"+i).val();
    var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
    var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
    var safe = next * lt ;
    $('#nowneed'+i).val(now);
    $('#nextneed'+i).val(next);
    $('#safe'+i).val(safe);
    $('#data8'+i).val(nowpeople);
    $('#data9'+i).val(nowline);
    $('#data10'+i).val(nowclass);
    $('#data11'+i).val(nowuse);
    $('#data12'+i).val(nowchange);
    $('#data13'+i).val(now);
    $('#data14'+i).val(nextpeople);
    $('#data15'+i).val(nextline);
    $('#data16'+i).val(nextclass);
    $('#data17'+i).val(nextuse);
    $('#data18'+i).val(nextchange);
    $('#data19'+i).val(next);
    $('#data20'+i).val(safe);
}
$(document).ready(function(){
    $("input").change(function(){

        for(var i = 0 ; i < count ; i++){
            var nowpeople = $("#nowpeople"+i).val();
            var nowline = $("#nowline"+i).val();
            var nowclass = $("#nowclass"+i).val();
            var nowuse = $("#nowuse"+i).val();
            var nowchange = $("#nowchange"+i).val();
            var nextpeople = $("#nextpeople"+i).val();
            var nextline = $("#nextline"+i).val();
            var nextclass = $("#nextclass"+i).val();
            var nextuse = $("#nextuse"+i).val();
            var nextchange = $("#nextchange"+i).val();
            var mpq = $("#mpq"+i).val();
            var lt = $("#lt"+i).val();
            var now = (nowpeople * nowclass * nowline * nowuse * nowchange ) / mpq;
            var next = (nextpeople * nextclass * nextline * nextuse * nextchange ) / mpq;
            var safe = next * lt ;
            $('#nowneed'+i).val(now);
            $('#nextneed'+i).val(next);
            $('#safe'+i).val(safe);
            $('#data8'+i).val(nowpeople);
            $('#data9'+i).val(nowline);
            $('#data10'+i).val(nowclass);
            $('#data11'+i).val(nowuse);
            $('#data12'+i).val(nowchange);
            $('#data13'+i).val(now);
            $('#data14'+i).val(nextpeople);
            $('#data15'+i).val(nextline);
            $('#data16'+i).val(nextclass);
            $('#data17'+i).val(nextuse);
            $('#data18'+i).val(nextchange);
            $('#data19'+i).val(next);
            $('#data20'+i).val(safe);
        }

    });
  });
