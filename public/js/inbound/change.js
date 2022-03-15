//only check one
$('.basic').on('change', function () {
    $('.basic').not(this).prop('checked', false);
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


var count = $("#count").val();


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


    $('.basic').on('click', function (e) {
        e.preventDefault();


        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        for (var j = 0; j < count; j++) {
            document.getElementById("amount" + j).style.borderColor = "";
            document.getElementById("newposition" + j).style.borderColor = "";
        }

        var i = $(this).val();
        var number = $("#number" + i).val();
        var stock = $("#stock" + i).val();
        var oldposition = $("#oldposition" + i).val();
        var client = $("#client" + i).val();
        var amount = $("#amount" + i).val();
        var newposition = $("#newposition" + i).val();

        // checked = $("input[type=checkbox]:checked").length;

        // if (!checked) {
        //     notyf.open({
        //         type: 'warning',
        //         message: Lang.get('inboundpageLang.nocheck'),
        //         duration: 3000, //miliseconds, use 0 for infinite duration
        //         ripple: true,
        //         dismissible: true,
        //         position: {
        //             x: "right",
        //             y: "bottom"
        //         }
        //     });
        //     return false;
        // }
        if (amount === '') {
            document.getElementById("amount" + i).classList.add("is-invalid");
            notyf.open({
                type: 'warning',
                message: Lang.get('inboundpageLang.enteramount'),
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
        if (newposition === null) {
            document.getElementById("newposition" + i).classList.add("is-invalid");
            notyf.open({
                type: 'warning',
                message: Lang.get('inboundpageLang.enterloc'),
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

        if (parseInt(amount) > parseInt(stock)) {
            notyf.open({
                type: 'warning',
                message: Lang.get('inboundpageLang.locchangeerr'),
                duration: 3000, //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
            document.getElementById("amount" + i).classList.add("is-invalid");
            return false;
        } else {
            var mess = Lang.get('inboundpageLang.coming') + ' : ' + oldposition + ' ' + Lang.get('inboundpageLang.transfer') + ' : ' + number + ' : ' +
                amount + ' ' + Lang.get('inboundpageLang.to') + ' ' + newposition + '\n' + Lang.get('inboundpageLang.client') + ' : ' + client;
            var sure = window.confirm(mess);
        }

        if (sure !== true) {
            return false;
        } else {
            $.ajax({
                type: 'POST',
                url: "changesubmit",
                data: {
                    client: client,
                    number: number,
                    oldposition: oldposition,
                    amount: amount,
                    newposition: newposition,
                    stock: stock
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
                    console.log(data.boolean);
                    var mess = Lang.get('inboundpageLang.locationchange') + ' ' + Lang.get('inboundpageLang.success');
                    alert(mess);
                    window.location.reload();

                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.warn(jqXHR.responseText);
                    alert(errorThrown);
                    window.location.reload();
                }
            });
        }
    });
});
