//only check one
$('.innumber').on('change', function () {
    $('.innumber').not(this).prop('checked', false);
});

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
            txtValue = $(this).find("input[id^='datab']").val();
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

    $('#inboundsearch').on('submit', function (e) {
        e.preventDefault();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var check = 0;
        checked = $("input[type=checkbox]:checked").length;

        if (!checked) {
            notyf.open({
                type: 'warning',
                message: Lang.get('oboundpageLang.nocheck'),
                duration: 3000, //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
            return false;
        }


        $("input:checkbox[name='innumber']:checked").each(function () {
            check = $(this).val();
        });


        var list = $('#dataa' + check).val();
        var number = $('#datab' + check).val();
        var name = $('#datac' + check).val();
        var format = $('#datad' + check).val();
        var client = $('#datae' + check).val();
        var bound = $('#dataf' + check).val();
        var amount = $('#datag' + check).val();
        var inpeople = $('#datah' + check).val();
        var time = $('#datai' + check).val();
        var mark = $('#dataj' + check).val();
        var inreason = $('#datak' + check).val();


        $.ajax({
            type: 'POST',
            url: "delete",
            data: {
                list: list,
                number: number,
                name:name ,
                format:format,
                amount: amount,
                bound: bound,
                inpeople: inpeople,
                client: client,
                inreason: inreason,
                time:time,
                mark:mark,
            },

            beforeSend: function () {
                // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
                $('body').loadingModal({
                    text: 'Loading...',
                    animation: 'circle'
                });

            },
            complete: function () {
                $('body').loadingModal('hide');
                $('body').loadingModal('destroy');
            },

            success: function (data) {

                var mess = Lang.get('oboundpageLang.delete') + Lang.get('oboundpageLang.success') +
                    Lang.get('oboundpageLang.inlist') + ' : ' + data.list + '\n' + Lang.get('oboundpageLang.client') + ' : ' + data.client + ' ' +
                    Lang.get('oboundpageLang.isn') + ' : ' + data.number;
                alert(mess);
                window.location.href = "inboundsearch";

            },
            error: function (err) {
                //庫存小於入庫數量
                if (err.status == 420) {
                    var mess = Lang.get('oboundpageLang.lessstock1') + '\n' + Lang.get('oboundpageLang.nowstock') + ' : ' + err.responseJSON.stock + ' ' +
                        Lang.get('oboundpageLang.inboundnum') + ' : ' + err.responseJSON.amount;
                    alert(mess);
                    return false;
                }
                //transaction error
                else if (err.status == 421) {
                    console.log(err.status);
                    var mess = err.responseJSON.message;
                    alert(mess);
                }
            },
        });

    });
});
