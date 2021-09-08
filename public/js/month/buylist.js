var counta = $("#counta").val();
var buyarraya = [];
var needarraya = [];
var buyarrayb = [];
var needarrayb = [];
var allneed;
var allbuy;
var countb = $("#countb").val();

const reducer = (accumulator, currentValue) => accumulator + currentValue;

for(var i = 0 ; i < counta ; i++){
    var srmnumbera = $("#srmnumbera"+i).val();
    var buyamounta = $("#buyamounta"+i).val();
    var pricea = $("#pricea"+i).val();
    var ratea = $("#ratea"+i).val();
    var nextneeda = $("#nextneeda"+i).val();
    var needmoneya = nextneeda * pricea * ratea ;
    var buymoneya = buyamounta * pricea * ratea ;
    $('#needmoneya'+i).val(needmoneya);
    $('#buymoneya'+i).val(buymoneya);
    $('#data17'+i).val(needmoneya);
    $('#data15'+i).val(buymoneya);
    $('#data0'+i).val(srmnumbera);
    buyarraya.push(buymoneya);
    needarraya.push(needmoneya);
}

for(var i = 0 ; i < countb ; i++){
    var srmnumberb = $("#srmnumberb"+i).val();
    var buyamountb = $("#buyamountb"+i).val();
    var priceb = $("#priceb"+i).val();
    var rateb = $("#rateb"+i).val();
    var nextneedb = $("#nextneedb"+i).val();
    var needmoneyb = nextneedb * priceb * rateb ;
    var buymoneyb = buyamountb * priceb * rateb ;
    $('#needmoneyb'+i).val(needmoneyb);
    $('#buymoneyb'+i).val(buymoneyb);
    $('#dataa17'+i).val(needmoneyb);
    $('#dataa15'+i).val(buymoneyb);
    $('#dataa0'+i).val(srmnumberb);
    buyarrayb.push(buymoneyb);
    needarrayb.push(needmoneyb);
}

allneed = (needarraya.reduce(reducer,0)) + (needarrayb.reduce(reducer,0));
allbuy = (buyarraya.reduce(reducer,0)) + (buyarrayb.reduce(reducer,0));

for(var i = 0 ; i < counta ; i++){
    var pricea = $("#pricea"+i).val();
    var ratea = $("#ratea"+i).val();
    var nextneeda = $("#nextneeda"+i).val();
    var buyamounta = $("#buyamounta"+i).val();
    var buymoneya = buyamounta * pricea * ratea ;
    var needmoneya = nextneeda * pricea * ratea ;
    var needpera = needmoneya / allneed * 100 ;
    var buypera = buymoneya / allbuy * 100 ;
    buypera = buypera.toFixed(2);
    needpera = needpera.toFixed(2);
    $('#needpera'+i).val(needpera + '%');
    $('#data18'+i).val(needpera);
    $('#buypera'+i).val(buypera + '%');
    $('#data16'+i).val(buypera);

}

for(var i = 0 ; i < countb ; i++){
    var priceb = $("#priceb"+i).val();
    var rateb = $("#rateb"+i).val();
    var nextneedb = $("#nextneedb"+i).val();
    var buyamountb = $("#buyamountb"+i).val();
    var buymoneyb = buyamountb * priceb * rateb ;
    var needmoneyb = nextneedb * priceb * rateb ;
    var needperb = needmoneyb / allneed * 100 ;
    var buyperb = buymoneyb / allbuy * 100 ;
    buyperb = buyperb.toFixed(2);
    needperb = needperb.toFixed(2);
    $('#needperb'+i).val(needperb + '%');
    $('#dataa18'+i).val(needperb);
    $('#buyperb'+i).val(buyperb + '%');
    $('#dataa16'+i).val(buyperb);
}

$(document).ready(function(){
    $("input").change(function(){
        for(var i = 0 ; i < counta ; i++){
            var buyamounta = $("#buyamounta"+i).val();
            var srmnumbera = $("#srmnumbera"+i).val();
            var pricea = $("#pricea"+i).val();
            var ratea = $("#ratea"+i).val();
            var buymoneya = buyamounta * pricea * ratea ;
            $('#buymoneya'+i).val(buymoneya);
            $('#data15'+i).val(buymoneya);
            $('#data0'+i).val(srmnumbera);
            buyarraya.splice(i,1,buymoneya);
        }
        for(var i = 0 ; i < countb ; i++){
            var buyamountb = $("#buyamountb"+i).val();
            var srmnumberb = $("#srmnumberb"+i).val();
            var priceb = $("#priceb"+i).val();
            var rateb = $("#rateb"+i).val();
            var buymoneyb = buyamountb * priceb * rateb ;
            $('#buymoneyb'+i).val(buymoneyb);
            $('#dataa15'+i).val(buymoneyb);
            $('#dataa0'+i).val(srmnumberb);
            buyarrayb.splice(i,1,buymoneyb);
        }
        allbuy = (buyarrayb.reduce(reducer,0)) + (buyarraya.reduce(reducer,0));

        for(var i = 0 ; i < counta ; i++){
            var srmnumbera = $("#srmnumbera"+i).val()
            var pricea = $("#pricea"+i).val();
            var ratea = $("#ratea"+i).val();
            var buyamounta = $("#buyamounta"+i).val();
            var buymoneya = buyamounta * pricea * ratea ;
            var buypera = buymoneya / allbuy * 100 ;
            buypera = buypera.toFixed(2);
            $('#buypera'+i).val(buypera + '%');
            $('#data16'+i).val(buypera);
            $('#data15'+i).val(buymoneya);
            $('#data13'+i).val(buyamounta);
            $('#data0'+i).val(srmnumbera);

        }
        for(var i = 0 ; i < countb ; i++){
            var srmnumberb = $("#srmnumberb"+i).val()
            var priceb = $("#priceb"+i).val();
            var rateb = $("#rateb"+i).val();
            var buyamountb = $("#buyamountb"+i).val();
            var buymoneyb = buyamountb * priceb * rateb ;
            var buyperb = buymoneyb / allbuy * 100 ;
            buyperb = buyperb.toFixed(2);
            $('#buyperb'+i).val(buyperb + '%');
            $('#dataa16'+i).val(buyperb);
            $('#dataa15'+i).val(buymoneyb);
            $('#dataa13'+i).val(buyamountb);
            $('#dataa0'+i).val(srmnumberb);
        }
    });
  });

