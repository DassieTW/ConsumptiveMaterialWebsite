$(document).ready(function () {

    $('#submitBtn').on('click', function(e) {
        e.preventDefault();
        var createSignal = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/checking/create_new_table",
            data: { createSignal: createSignal },
            dataType: 'json',              // let's set the expected response format
            beforeSend: function () {
                $('body').loadingModal({
                    text: 'Loading...',
                    animation: 'circle'
                });
            },
            complete: function () {
                $('body').loadingModal('hide');
            },
            success: function (response) {
                
            },
            error: function (err) {
                if (err.status == 422) { // when status code is 422, it's a validation issue
                    // console.log(err.responseJSON.message); // test

                    // you can loop through the errors object and show it to the user
                    // console.warn(err.responseJSON.errors); // test
                    // display errors on each form field
                    $.each(err.responseJSON.errors, function (i, error) {
                        var el = $(document).find('[name="' + i + '"]');
                        // console.log(el.siblings(".input-group-text").length); // test
                        el.addClass("is-invalid");
                        if (el.siblings(".input-group-text").length > 0) {
                            if ($('.invalid-feedback').length === 0) {
                                $('#barcode2').after($('<span class="invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                            } // if
                        } // if
                        else {
                            el.after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                        } // if else 
                    });
                } // if error 422
                else if (err.status == 420) {  // if no result
                    $('#searchIn').addClass("is-invalid");
                    $('#searchIn').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_isn') + '</strong></span>'));
                } // else if
                else {
                    // Lang = new Lang();
                    console.log(err.status); // test
                } // else
            }
        }); // end of ajax
    });

}); // on document ready