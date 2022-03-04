$(document).ready(function () {


    function quickSearch() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = $("#numbersearch").val();
        //var isISN = $("#toggle-state").is(":checked");
        console.log(input); // test
        // filter = input.value;
        // Loop through all table rows, and hide those who don't match the search query
        $('.isnRows').each(function (i, obj) {
            txtValue = $(this).find("input[id^='data']").val();
            // console.log("now checking text : " + txtValue); // test
            if (txtValue.indexOf(input) > -1) {
                obj.style.display = "";

            } else {
                obj.style.display = "none";
            } // if else
        });
        $('.isnRows1').each(function (i, obj) {
            txtValue = $(this).find("input[id^='data1']").val();
            // console.log("now checking text : " + txtValue); // test
            if (txtValue.indexOf(input) > -1) {
                obj.style.display = "";

            } else {
                obj.style.display = "none";
            } // if else
        });
        $('.isnRows2').each(function (i, obj) {
            txtValue = $(this).find("input[id^='data2']").val();
            // console.log("now checking text : " + txtValue); // test
            if (txtValue.indexOf(input) > -1) {
                obj.style.display = "";

            } else {
                obj.style.display = "none";
            } // if else
        });
    } // quickSearch function


    $("#numbersearch").on('input', function (e) {
        e.preventDefault();
        quickSearch();
    });


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
