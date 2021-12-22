$(document).ready(function () {

    var count = $("#count").val();
    for(var i = 0 ; i < count ; i++){
        var safe = $("#safea"+i).html();
        var stock = $("#stocka"+i).html();
        var percent = stock / safe ;
        if(percent < 0.5)
        {
            $("#safea"+i).css("background-color", "red");
        }
        else if(percent >= 0.5 && percent < 0.8)
        {
            $("#safea"+i).css("background-color", "orange");
        }
        else if(percent >= 0.8)
        {
            $("#safea"+i).css("background-color", "yellow");
        }

    }

    var count1 = $("#count1").val();
    for(var i = 0 ; i < count1 ; i++){
        var safe = $("#safeb"+i).html();
        var stock = $("#stockb"+i).html();
        var percent = stock / safe ;
        if(percent < 0.5)
        {
            $("#safeb"+i).css("background-color", "red");
        }
        else if(percent >= 0.5 && percent < 0.8)
        {
            $("#safeb"+i).css("background-color", "orange");
        }
        else if(percent >= 0.8)
        {
            $("#safeb"+i).css("background-color", "yellow");
        }

    }

});
