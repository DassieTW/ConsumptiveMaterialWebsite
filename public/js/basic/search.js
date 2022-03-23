$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ready(function () {

    $(".form-check-input").on("change", function () {

        var check = $('input[name=numberradio]:checked').val();
        if (check == 1 ) {
            // $("#number").prop('required', true);
            $("#numberarea").prop('required', false);
        } else {
            $("#number").prop('required', false);
            $("#numberarea").prop('required', true);
        }

    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form1').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var radio = $('input[name=numberradio]:checked', '#form1').val();
        var input ;

        if(radio == 1)
        {
            input = $("#number").val();
        }
        else
        {
            input = $("#numberarea").val().split(/\r?\n/);

        }


        $.ajax({
            type: "POST",
            url: "materialsearch",
            data: {
                input: input,
                radio: radio,
            },
            dataType: "json", // expected respose datatype from server
            //async: false,

            beforeSend: function () {
                // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
                $("body").loadingModal({
                    text: "Loading...",
                    animation: "circle",
                });
            },
            complete: function () {
                $("body").loadingModal("hide");
            },
            success: function (data) {

                //console.log(data.input)
                window.location.href = "materialsearch";

            },
            error: function (err) {
                //transaction error
                if (err.status == 409) {
                    console.log(err.status);
                }
            },
        });


    });

});
