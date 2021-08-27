var count = $("#count").val();
for(var i = 0 ; i < count ; i++){
    var day = $("#staydaya"+i).html();

    if(day > 30 && day <= 60)
    {
        $("#staydaya"+i).css("background-color", "yellow");
    }
    else if(day > 60 && day <= 90)
    {
        $("#staydaya"+i).css("background-color", "orange");
    }
    else if(day > 90)
    {
        $("#staydaya"+i).css("background-color", "red");
    }

}
