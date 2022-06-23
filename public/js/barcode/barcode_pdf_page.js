$(document).ready(function () {

    console.log('hi'); //test
    // var sendd = confirm(Lang.get('barcodeGenerator.temp_save_success'));
    var sendd = 'wat'; // test
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "post",
        url: "/barcode/cleanupAllBarcodes",
        dataType: 'json', // expected respose datatype from server
        data: { sendd: sendd },
        success: function (response) {
            console.log(response); // test
        },
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
        error: function (err) {
            console.log(err.status); // test
        } // error
    });    // ajax

}); // on document ready