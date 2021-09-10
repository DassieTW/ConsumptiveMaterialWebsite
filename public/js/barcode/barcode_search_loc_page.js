function appenSVg() {
    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    var path = document.createElementNS("http://www.w3.org/2000/svg", 'path');
    path.setAttribute('d', "M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z");
    svg.setAttribute("width", "16");
    svg.setAttribute("height", "16");
    svg.setAttribute("fill", "#c94466");
    svg.setAttribute("class", "bi bi-x-circle-fill");
    svg.setAttribute("viewBox", "0 0 16 16");
    svg.appendChild(path);
    $('.deleteBtn').append(svg);
    $('.deleteBtn').on('click', function (e) {
        e.preventDefault();

        if (sessionStorage.getItem('locArray') !== null) {
            var locArray = JSON.parse(sessionStorage.getItem('locArray'));
            var locCount = JSON.parse(sessionStorage.getItem('locCount'));
            var locSepCount = JSON.parse(sessionStorage.getItem('locSepCount'));
            var str = $(this).attr('id');
            let index2 = $.inArray(str, locArray);
            if (index2 !== -1) { // delete a loc
                locArray.splice(index2, 1);
                locSepCount.splice(index2, 1);
                locCount = locCount - 1;
                sessionStorage.setItem('locArray', JSON.stringify(locArray));
                sessionStorage.setItem('locCount', JSON.stringify(locCount));
                sessionStorage.setItem('locSepCount', JSON.stringify(locSepCount));
            } // if
        } // if locArray is set

        $(this).parent().parent().remove();
    }); // on delete btn click

    $('.printNum2').on('input', function (e) {
        //restrict input to numbers
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        if (this.value === "") {
            this.value = 0;
        } // if
        var locArray = JSON.parse(sessionStorage.getItem('locArray'));

        var locSepCount = JSON.parse(sessionStorage.getItem('locSepCount'));

        var strr = $(this).attr('id');
        const myArr = strr.split("__");
        let index2 = -1;
        $.grep(locArray, function (loc, index) {
            if (loc === myArr[0]) {
                index2 = index;
                return true;
            } // if
            else {
                return false;
            } // else
        });

        if (index2 !== -1) { // update a print count
            locSepCount[index2] = parseInt($(this).val()) + 0;
            sessionStorage.setItem('locSepCount', JSON.stringify(locSepCount));
        } // if
    }); // on printNum2 input
} // appenSVg

function UpdateTempField() {
    // clean up previous
    $('.barcodePreview').remove();

    var locArray = JSON.parse(sessionStorage.getItem('locArray'));
    var locCount = JSON.parse(sessionStorage.getItem('locCount'));
    var locSepCount = JSON.parse(sessionStorage.getItem('locSepCount'));

    for (let ty = 0; ty < locCount; ty++) {
        $('#tableHead2').after($('<tr class="barcodePreview align-items-center" id="' + locArray[ty] + '">' +
            '<td class="col col-auto align-items-center px-0 m-0"><a class="deleteBtn" id="' + locArray[ty] + '" value="' + locArray[ty] + '">' +
            '</a></td>' +
            '<td class="col col-auto align-items-center px-0 m-0"><span>' +
            locArray[ty] +
            '</span>' +
            '</td><td class="col col-auto align-items-center px-0 m-0">' +
            '<input inputmode="numeric" type="number" min="0" id="' + locArray[ty] + '" class="printNum2" name="printNum2" style="width: 6ch;"value="' +
            locSepCount[ty] + '">'
            + '</td></tr>'
        ));
    } // for

    appenSVg();
};

function mergeTwoArrays(Array1, Array2) {
    var Array3 = [];
    for (let a = 0; a < Array1.length; a++) {
        var temp = Array1[a] + Array2[a];
        Array3.push(temp);
    } // for

    return Array3;
} // mergeTwoArrays

$(document).ready(function () {
    $('#searchForm').on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var searchIn = $("#searchIn").val();
        $("#searchIn").val("");
        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        $.ajax({
            type: "post",
            url: "/barcode/search_loc",
            data: { searchIn: searchIn },
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
                var myObjs = JSON.parse(JSON.stringify(response.data));
                var locSheet = [];
                for (let a = 0; a < myObjs.length; a++) {
                    locSheet.push(myObjs[a].儲存位置);
                } // for

                var locCount = 0;
                var locResult = [];
                var locSepCountResult = [];

                var locSepCount = 1;
                for (let a = 0; a < locSheet.length; a++) {
                    for (let b = a + 1; b < locSheet.length; b++) {
                        if (locSheet[a] === locSheet[b]) {
                            locSepCount++;
                            locSheet.splice(b, 1);
                        } // if
                    } // for

                    locCount++;
                    locResult.push(locSheet[a]);

                    locSepCountResult.push(locSepCount);
                    locSepCount = 1;
                } // for

                sessionStorage.setItem('locCount', JSON.stringify(locCount));
                sessionStorage.setItem('locArray', JSON.stringify(locResult));
                sessionStorage.setItem('locSepCount', JSON.stringify(locSepCountResult));

                UpdateTempField();
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
                    $('#searchIn').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_loc') + '</strong></span>'));
                } // else if
                else {
                    // Lang = new Lang();
                    console.log(err.status); // test
                } // else
            } // error
        }); // end of ajax
    }); // on searchForm Submit

    $('#printBtn').on('click', function (e) {  // print all barcodes in the temp field
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var sID = $("#sID").val();
        var sendingISNArray = null;
        var sendingISNNameArray = null;
        var sendingISNSepCount = null;

        var sendingLocArray = null;
        var sendingLocSepCount = null;

        if (sessionStorage.hasOwnProperty('locCount')) {
            sendingLocArray = JSON.parse(sessionStorage.getItem('locArray'));
            var locCount = JSON.parse(sessionStorage.getItem('locCount'));
            sendingLocSepCount = JSON.parse(sessionStorage.getItem('locSepCount'));
        } // if

        if (sessionStorage.hasOwnProperty('locCount')) {
            $.ajax({
                url: "/barcode/printBarcode",
                type: 'post',
                cache: false,
                data: {
                    isnArray: sendingISNArray, isnNameArray: sendingISNNameArray, isnSepCount: sendingISNSepCount,
                    locArray: sendingLocArray, locSepCount: sendingLocSepCount, sID: sID
                },
                dataType: 'json', // let's set the expected response format
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
                    window.location.href = '/barcode/printingPage';
                },
                error: function (err) {
                    console.log(err.responseJSON.message); // test
                    notyf.error({
                        message: Lang.get('barcodeGenerator.temp_save_error') + Lang.get('barcodeGenerator.print'),
                        duration: 5000,   //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom"
                        }
                    });
                } // if error
            }); // end of ajax
        } // if
        else {
            notyf.open({
                type: 'warning',
                message: Lang.get('barcodeGenerator.nothing_to_print'),
                duration: 5000,   //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
        } // else

        sessionStorage.clear();
        localStorage.clear();
    });
}); // on document ready

