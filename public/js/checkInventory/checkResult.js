$(document).ready(function () {

    (function () { // starting show on document ready
        if (document.getElementById("toggle-state").checked) {
            $('#toggle-state-text').text(Lang.get('checkInvLang.isn'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.input_isn_barcode'));
        } // if
        else {
            $('#toggle-state-text').text(Lang.get('checkInvLang.loc'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.input_loc_barcode'));
        } // else

        $("#texBox").focus();

        // console.log($('#serialList li').length); // test
        if ($('#serialList li').length == 0) {
            $('#continueT').text(Lang.get('checkInvLang.no_table_found'));
            $('#continueT').parent().find('ul').append('<li><a class="dropdown-item" id="newTableLink" href="#">' + Lang.get('checkInvLang.create_new_table') + '</a></li>');
            $('#newTableLink').on('click', function (e) {
                e.preventDefault();
                window.location.href = "/checking/create_new_table";
            });
        } // if
    })();

    $('#toggle-state').on('change', function () { // 目標改變
        // 'this' will contain a reference to the checkbox
        if (this.checked) {
            $('#toggle-state-text').text(Lang.get('checkInvLang.isn'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.input_isn_barcode'));
        } else {
            $('#toggle-state-text').text(Lang.get('checkInvLang.loc'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.input_loc_barcode'));
        } // if else

        $("#texBox").focus();
    });  // 目標改變

    $('.serialNum').on('click', function (e) {
        $('.serialNum').removeClass('active'); // remove all active class name
        $('#continueT').text($(this).text());
        var clickedTableName = $('#continueT').text();
        $('.serialNum').each(function (i, obj) {
            // console.log($(this).text()); // test
            if ($(this).text() === $('#continueT').text()) {
                $(this).addClass('active'); // add the active class name
            } // if
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/checking/set_wanted_table",
            data: { tableName: clickedTableName },
            dataType: 'json',              // let's set the expected response format
            beforeSend: function () {
                $('body').loadingModal({
                    text: 'Loading...',
                    animation: 'circle'
                });
            },
            complete: function () {
                $('body').loadingModal('hide');
                $('body').loadingModal('destroy');
            },
            success: function (response) {
                // do nothing
            },
            error: function (err) {
                if (err.status == 420) {  // if no result
                    console.log('set session failed.');
                } // else if
                else {
                    console.log(err.status); // test
                } // else
            } // error
        }); // end of ajax

        $("#texBox").focus();
    }); // on drop down menu click

    
}); // on document ready