$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


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
            txtValue = $(this).find("input[id^='number']").val();
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
});
