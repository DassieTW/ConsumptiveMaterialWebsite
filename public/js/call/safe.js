$(document).ready(function () {

    var count = $("#count").val();
    for(var i = 0 ; i < count ; i++){
        var safe = $("#safea"+i).html();
        var stock = $("#stocka"+i).html();
        var percent = stock / safe ;
        console.log(percent);

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
        else
        {
            $("#safea"+i).css("background-color", "red");
        }

    }

    var count1 = $("#count1").val();
    for(var i = 0 ; i < count1 ; i++){
        var safe = $("#safeb"+i).html();
        var stock = $("#stockb"+i).html();
        var percent = stock / safe ;
        console.log(percent);

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
        else
        {
            $("#safeb"+i).css("background-color", "red");
        }

    }

    var count2 = $("#count2").val();
    for(var i = 0 ; i < count2 ; i++){
        var safe = $("#safec"+i).html();
        var stock = $("#stockc"+i).html();
        var percent = stock / safe ;
        console.log(percent);
        if(percent < 0.5)
        {
            $("#safec"+i).css("background-color", "red");
        }
        else if(percent >= 0.5 && percent < 0.8)
        {
            $("#safec"+i).css("background-color", "orange");
        }
        else if(percent >= 0.8)
        {
            $("#safec"+i).css("background-color", "yellow");
        }
        else
        {
            $("#safec"+i).css("background-color", "red");
        }

    }

});
