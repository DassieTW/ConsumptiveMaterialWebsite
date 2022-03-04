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
    } // quickSearch function


    $("#numbersearch").on('input', function (e) {
        e.preventDefault();
        quickSearch();
    });

    var count = $("#count").val();
    for (var i = 0; i < count; i++) {
        var day = $("#staydaya" + i).html();

        if (day > 30 && day <= 60) {
            $("#staydaya" + i).css("background-color", "yellow");
        } else if (day > 60 && day <= 90) {
            $("#staydaya" + i).css("background-color", "orange");
        } else if (day > 90) {
            $("#staydaya" + i).css("background-color", "red");
        }

    }
});
