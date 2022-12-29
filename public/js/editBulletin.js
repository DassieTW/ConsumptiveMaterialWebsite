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
        if (sessionStorage.getItem('isnArray') !== null) {
            var isnArray = JSON.parse(sessionStorage.getItem('isnArray'));
            var isnName = JSON.parse(sessionStorage.getItem('isnName'));
            var isnCount = JSON.parse(sessionStorage.getItem('isnCount'));
            var isnSepCount = JSON.parse(sessionStorage.getItem('isnSepCount'));

            var str = $(this).attr('id');
            const myArr = str.split("__");
            // console.log(myArr); // test
            // console.log(myArr.length); // test
            let index1 = -1;
            $.grep(isnArray, function (isn, index) {
                // console.log(isnName[index]); // test
                if (isn === myArr[0] && isnName[index] === myArr[1]) {
                    index1 = index;
                    return true;
                } // if
                else {
                    return false;
                } // else
            });

            if (index1 !== -1) { // delete a isn
                isnArray.splice(index1, 1);
                isnName.splice(index1, 1);
                isnSepCount.splice(index1, 1);
                isnCount = isnCount - 1;
                sessionStorage.setItem('isnArray', JSON.stringify(isnArray));
                sessionStorage.setItem('isnName', JSON.stringify(isnName));
                sessionStorage.setItem('isnCount', JSON.stringify(isnCount));
                sessionStorage.setItem('isnSepCount', JSON.stringify(isnSepCount));
            } // if
        } // if isnArray is set

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

    $('.printNum').on('input', function (e) {
        //restrict input to numbers
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        if (this.value === "") {
            this.value = 0;
        } // if

        var isnArray = JSON.parse(sessionStorage.getItem('isnArray'));
        var isnName = JSON.parse(sessionStorage.getItem('isnName'));

        var isnSepCount = JSON.parse(sessionStorage.getItem('isnSepCount'));

        var strr = $(this).attr('id');
        const myArr = strr.split("__");
        // console.log(myArr); // test
        // console.log(myArr.length); // test
        let index2 = -1;
        $.grep(isnArray, function (isn, index) {
            // console.log(isnName[index]); // test
            if (isn === myArr[0] && isnName[index] === myArr[1]) {
                index2 = index;
                return true;
            } // if
            else {
                return false;
            } // else
        });

        if (index2 !== -1) { // update a print count
            isnSepCount[index2] = parseInt($(this).val()) + 0;
            sessionStorage.setItem('isnSepCount', JSON.stringify(isnSepCount));
        } // if
    }); // on printNum input

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
        // console.log(myArr); // test
        // console.log(myArr.length); // test
        let index2 = -1;
        $.grep(locArray, function (loc, index) {
            // console.log(isnName[index]); // test
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

$(document).ready(function () {

    (function () {
        if (sessionStorage.hasOwnProperty('isnCount')) {
            var DelorNot = true;
            var isISN = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "/barcode/seenDelete",  // refer to the route name in web.php
                data: { DelorNot: DelorNot, isISN: isISN },
                dataType: 'json',              // let's set the expected response format
                success: function (data) {
                    console.log(data.message);
                },
                error: function (err) {
                    console.warn(err);
                } // error
            });
        } // if

        if (sessionStorage.hasOwnProperty('locCount')) {
            var DelorNot = true;
            var isISN = false;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: "/barcode/seenDelete",  // refer to the route name in web.php
                data: { DelorNot: DelorNot, isISN: isISN },
                dataType: 'json',              // let's set the expected response format
                success: function (data) {
                    console.log(data.message);
                },
                error: function (err) {
                    console.warn(err);
                } // error
            });
        } // if

        if (! sessionStorage.hasOwnProperty('allMats')) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({   // get all isn and pName for bringing out pName if it existed in database
                type: "post",
                url: "/barcode/search_isn",
                data: { searchIn: "" },
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
                    AllMatsData = JSON.parse(JSON.stringify(response.data));
                    sessionStorage.setItem("allMats", JSON.stringify(response.data));
                    // console.log(AllMatsData); // test
                },
                error: function (err) {
                    console.log(err.status); // test
                    // console.log(err); // test
                } // error
            }); // end of ajax
        } // if
        else {
            AllMatsData = JSON.parse(sessionStorage.getItem("allMats"));
        } // else

        if( sessionStorage.hasOwnProperty('locCount') || sessionStorage.hasOwnProperty('isnCount') ) {
            notyf.success({
                message: Lang.get('barcodeGenerator.temp_save_success'),
                duration: 5000,   //miliseconds, use 0 for infinite duration
                ripple: true,
                dismissible: true,
                position: {
                    x: "right",
                    y: "bottom"
                }
            });
        } // if
    })();

    $('#isnForm').on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            type: "post",
            url: "/barcode/barcode_isn",
            data: { barcode2: barcode2, pName: pName, isIsn: isIsn, toSess: toSess, fName: fName },
            dataType: 'json',              // let's set the expected response format
            beforeSend: function () {
                console.log('sup, loading modal triggered !');
                // e.preventDefault();return false;  // test
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
                else {
                    console.log(err.status); // test
                } // else
            }
        }); // end of ajax
    }); // on submit

}); // on document ready

// $(window).on('beforeunload', function() {
//     sessionStorage.clear();
// });
