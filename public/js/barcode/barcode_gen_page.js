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

function UpdateTempField() {
    // clean up previous
    $('.barcodePreview').remove();

    var isnArray = JSON.parse(sessionStorage.getItem('isnArray'));
    var isnName = JSON.parse(sessionStorage.getItem('isnName'));
    var isnCount = JSON.parse(sessionStorage.getItem('isnCount'));
    var isnSepCount = JSON.parse(sessionStorage.getItem('isnSepCount'));

    for (let ty = 0; ty < isnCount; ty++) {
        $('#tableHead').after($('<tr class="barcodePreview isn align-items-center" id="' + isnArray[ty] + '__' + isnName[ty] + '">' +
            '<td class="col col-auto align-items-center px-0 m-0"><a class="deleteBtn" id="' + isnArray[ty] + '__' + isnName[ty] + '" value="' + isnArray[ty] + '__' + isnName[ty] + '">' +
            '</a></td>' +
            '<td class="col col-auto align-items-center px-0 m-0"><span>' +
            isnArray[ty] +
            '</span><br><span>' +
            isnName[ty] +
            '</span></td><td class="col col-auto align-items-center px-0 m-0">' +
            '<input inputmode="numeric" type="number" min="0" id="' + isnArray[ty] + '__' + isnName[ty] + '" class="printNum" name="printNum" style="width: 8ch;"value="' +
            isnSepCount[ty] + '">'
            + '</td></tr>'
        ));

    } // for

    var locArray = JSON.parse(sessionStorage.getItem('locArray'));
    var locCount = JSON.parse(sessionStorage.getItem('locCount'));
    var locSepCount = JSON.parse(sessionStorage.getItem('locSepCount'));

    for (let ty = 0; ty < locCount; ty++) {
        $('#tableHead2').after($('<tr class="barcodePreview loc align-items-center" id="' + locArray[ty] + '">' +
            '<td class="col col-auto align-items-center px-0 m-0"><a class="deleteBtn" id="' + locArray[ty] + '" value="' + locArray[ty] + '">' +
            '</a></td>' +
            '<td class="col col-auto align-items-center px-0 m-0"><span>' +
            locArray[ty] +
            '</span>' +
            '</td><td class="col col-auto align-items-center px-0 m-0">' +
            '<input inputmode="numeric" type="number" min="0" id="' + locArray[ty] + '" class="printNum2" name="printNum2" style="width: 8ch;"value="' +
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

function CallPhpSpreadSheetToGetData(fileName) {
    // console.log( " Yo ! Sexy ! " + fileName ) ; // test
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "post",
        url: "/barcode/batch_upload_decompose",
        data: { fileName: fileName },
        dataType: 'json',              // let's set the expected response format
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
        success: function (response) {
            // console.log(response.isnSheet.length); // test
            // console.log(response.nameSheet); // test
            // console.log(response.locSheet); // test
            // console.log(response.message); // test

            var isnSheet = response.isnSheet;
            var nameSheet = response.nameSheet;
            var locSheet = response.locSheet;

            var isnCount = 0;
            var isnResult = [];
            var nameResult = [];
            var isnSepCountResult = [];

            var isnSepCount = 1;
            if (sessionStorage.getItem('isnArray') !== null) {
                for (let a = 0; a < isnSheet.length; a++) {
                    isnResult = JSON.parse(sessionStorage.getItem('isnArray'));
                    nameResult = JSON.parse(sessionStorage.getItem('isnName'));
                    var combination = mergeTwoArrays(isnResult, nameResult);
                    isnCount = JSON.parse(sessionStorage.getItem('isnCount'));
                    isnSepCountResult = JSON.parse(sessionStorage.getItem('isnSepCount'));
                    var key1 = -1;
                    if (nameSheet[a] === null) {
                        key1 = $.inArray(isnSheet[a] + "", combination);
                    } else {
                        key1 = $.inArray(isnSheet[a] + nameSheet[a], combination);
                    } // else

                    if (key1 !== -1) { // if theres duplicate in upload file and/or the same as the one gen in website
                        isnSepCountResult[key1] = isnSepCountResult[key1] + 1;
                    } // if
                    else { // the upload file has a new entry
                        isnResult.push(isnSheet[a]);
                        if (nameSheet[a] === null) {
                            nameResult.push("");
                        } else {
                            nameResult.push(nameSheet[a]);
                        } // else

                        isnCount++;
                        isnSepCountResult.push(1);
                    } // else

                    sessionStorage.setItem('isnCount', JSON.stringify(isnCount));
                    sessionStorage.setItem('isnArray', JSON.stringify(isnResult));
                    sessionStorage.setItem('isnName', JSON.stringify(nameResult));
                    sessionStorage.setItem('isnSepCount', JSON.stringify(isnSepCountResult));
                } // for
            } // if
            else if (sessionStorage.getItem('isnArray') === null) {
                for (let a = 0; a < isnSheet.length; a++) {
                    for (let b = a + 1; b < isnSheet.length; b++) {
                        if (isnSheet[a] === isnSheet[b] && nameSheet[a] === nameSheet[b]) {
                            isnSepCount++;
                            isnSheet.splice(b, 1);
                            nameSheet.splice(b, 1);
                        } // if
                    } // for

                    isnCount++;
                    isnResult.push(isnSheet[a]);
                    if (nameSheet[a] === null) {
                        nameResult.push("");
                    } else {
                        nameResult.push(nameSheet[a]);
                    } // else
                    isnSepCountResult.push(isnSepCount);
                    isnSepCount = 1;
                } // for

                sessionStorage.setItem('isnCount', JSON.stringify(isnCount));
                sessionStorage.setItem('isnArray', JSON.stringify(isnResult));
                sessionStorage.setItem('isnName', JSON.stringify(nameResult));
                sessionStorage.setItem('isnSepCount', JSON.stringify(isnSepCountResult));
            } // else if

            var locCount = 0;
            var locResult = [];
            var locSepCountResult = [];

            var locSepCount = 1;
            if (sessionStorage.getItem('locArray') !== null) {
                for (let a = 0; a < locSheet.length; a++) {
                    locResult = JSON.parse(sessionStorage.getItem('locArray'));
                    locCount = JSON.parse(sessionStorage.getItem('locCount'));
                    locSepCountResult = JSON.parse(sessionStorage.getItem('locSepCount'));
                    var key1 = $.inArray(locSheet[a], locResult);
                    if (key1 !== -1) { // if theres duplicate in upload file and/or the same as the one gen in website
                        locSepCountResult[key1] = locSepCountResult[key1] + 1;
                    } // if
                    else { // the upload file has a new entry
                        locResult.push(locSheet[a]);
                        locCount++;
                        locSepCountResult.push(1);
                    } // else

                    sessionStorage.setItem('locCount', JSON.stringify(locCount));
                    sessionStorage.setItem('locArray', JSON.stringify(locResult));
                    sessionStorage.setItem('locSepCount', JSON.stringify(locSepCountResult));
                } // for

            } // if
            else if (sessionStorage.getItem('locArray') === null) {
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
            } // else if

            UpdateTempField();
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
                            el.parent().after($('<span class="invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                        } // if
                    } // if
                    else {
                        el.after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                    } // if else 
                });
            } // if error 422
            else {
                // Lang = new Lang();
                console.log(err); // test
                notyf.error({
                    message: Lang.get('barcodeGenerator.temp_save_error') + err.status,
                    duration: 0,   //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });
            } // else
        } // error
    }); // end of ajax

} // CallPhpSpreadSheetToGetData

var AllMatsData;
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
        // var barcode1 = $("#barcode1").val();
        var barcode2 = $("#barcode2").val().toUpperCase();
        var pName = $("#pName").val();
        var isIsn = $("#isIsn").val();
        var toSess = $("#toSess").val();
        var fName = $("#fName").val();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

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
                if (toSess === 'true') {
                    if (isIsn === 'true' && sessionStorage.hasOwnProperty('isnCount')) {
                        // if we already got this isn
                        var isnArray = JSON.parse(sessionStorage.getItem('isnArray'));
                        var nameArray = JSON.parse(sessionStorage.getItem('isnName'));
                        var combination = mergeTwoArrays(isnArray, nameArray);

                        // var keyy = $.inArray(barcode1 + '-' + barcode2 + pName, combination);
                        var keyy = $.inArray(barcode2 + pName, combination);
                        if (keyy !== -1) {
                            var tt = JSON.parse(sessionStorage.getItem('isnSepCount'));
                            tt[keyy] = tt[keyy] + 1;
                            sessionStorage.setItem('isnSepCount', JSON.stringify(tt));
                        } // if
                        else {
                            var a = JSON.parse(sessionStorage.getItem('isnCount'));
                            a = a + 1;
                            sessionStorage.setItem('isnCount', JSON.stringify(a));

                            var b = JSON.parse(sessionStorage.getItem('isnArray'));
                            // b.push(barcode1 + '-' + barcode2);
                            b.push(barcode2);
                            sessionStorage.setItem('isnArray', JSON.stringify(b));

                            var c = JSON.parse(sessionStorage.getItem('isnSepCount'));
                            c.push(1);
                            sessionStorage.setItem('isnSepCount', JSON.stringify(c));

                            var d = JSON.parse(sessionStorage.getItem('isnName'));
                            d.push(pName);
                            sessionStorage.setItem('isnName', JSON.stringify(d));
                        } // else
                    } // if
                    else if (isIsn === 'true' && !sessionStorage.hasOwnProperty('isnCount')) {
                        sessionStorage.setItem('isnCount', 1);
                        // var iisn = barcode1 + '-' + barcode2;
                        var iisn = barcode2;
                        var e = [iisn,];
                        sessionStorage.setItem('isnArray', JSON.stringify(e));
                        e = [1,];
                        sessionStorage.setItem('isnSepCount', JSON.stringify(e));
                        e = [pName,];
                        sessionStorage.setItem('isnName', JSON.stringify(e));
                    } // else if
                } // if

                location.reload();
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
                    // Lang = new Lang();
                    console.log(err.status); // test
                } // else
            }
        }); // end of ajax
    }); // on isnForm submit

    $('#isnForm').on('input', function (e) {
        if ($('#barcode2').val().length === 12) { // when it fits the the 12 digits isn
            var searchIn = $("#barcode2").val().toUpperCase();
            // clean up previous input results
            $('.is-invalid').removeClass('is-invalid');
            $(".invalid-feedback").remove();

            let i = 0;
            for (i = 0; i < AllMatsData.length; i++) {
                if (searchIn === AllMatsData[i].料號) {
                    $("#pName").val(AllMatsData[i].品名);
                    $("#pName").focus();
                    break;
                } // if
            } // for

            if (i === AllMatsData.length) { // isn not found in database
                $("#pName").focus();
            } // if

        } // if
    }); // on isnForm input

    $('#locForm').on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var barcode3 = $("#barcode3").val();
        var isIsn = $("#isIsn2").val();
        var toSess = $("#toSess2").val();
        var fName = $("#fName2").val();

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        $.ajax({
            type: "post",
            url: "/barcode/barcode_loc",
            data: { barcode3: barcode3, isIsn: isIsn, toSess: toSess, fName: fName },
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
                if (toSess === 'true') {
                    if (isIsn === 'false' && sessionStorage.hasOwnProperty('locCount')) {
                        // if we already got this loc
                        var keyy = $.inArray(barcode3, JSON.parse(sessionStorage.getItem('locArray')));
                        if (keyy !== -1) {
                            var tt = JSON.parse(sessionStorage.getItem('locSepCount'));
                            tt[keyy] = tt[keyy] + 1;
                            sessionStorage.setItem('locSepCount', JSON.stringify(tt));
                        } // if
                        else {
                            var a = JSON.parse(sessionStorage.getItem('locCount'));
                            a = a + 1;
                            sessionStorage.setItem('locCount', JSON.stringify(a));

                            var b = JSON.parse(sessionStorage.getItem('locArray'));
                            b.push(barcode3);
                            sessionStorage.setItem('locArray', JSON.stringify(b));

                            var c = JSON.parse(sessionStorage.getItem('locSepCount'));
                            c.push(1);
                            sessionStorage.setItem('locSepCount', JSON.stringify(c));
                        } // else
                    } // if
                    else if (isIsn === 'false' && !sessionStorage.hasOwnProperty('locCount')) {
                        sessionStorage.setItem('locCount', 1);
                        var lloc = barcode3;
                        var e = [lloc,];
                        sessionStorage.setItem('locArray', JSON.stringify(e));
                        e = [1,];
                        sessionStorage.setItem('locSepCount', JSON.stringify(e));
                    } // else if
                } // if

                location.reload();
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
                                el.parent().after($('<span class="invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                            } // if
                        } // if
                        else {
                            el.after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                        } // if else 
                    });
                } // if error 422
                else {
                    // Lang = new Lang();
                    console.log(err.status); // test
                } // else
            } // error
        }); // end of ajax
    }); // on locForm Submit

    // upload file using ajax
    $('#fileUpForm').on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        var file_data = $('#batchUp').prop('files')[0];   //取得上傳檔案
        var form_data = new FormData();  //建構new FormData()
        form_data.append('batchUp', file_data);
        // console.log(form_data); // test

        $.ajax({
            url: "/barcode/batch_upload",
            type: 'post',
            cache: false,
            data: form_data,
            contentType: false,
            processData: false,
            dataType: 'json', // let's set the expected response format
            beforeSend: function () {
                // console.log('sup, loading modal triggered !'); // test
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
            success: function (response) {
                // console.log(response.filename); // test
                $('#batchUp').val('');
                notyf.success({
                    message: Lang.get('barcodeGenerator.upload_save_success'),
                    duration: 5000,   //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });

                CallPhpSpreadSheetToGetData(response.filename);
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
                                el.parent().after($('<span class="invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                            } // if
                        } // if
                        else {
                            el.after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + error[0] + '</strong></span>'));
                        } // if else 
                    });
                } // if error 422
                else if (err.status == 469) { // if there's no file selected
                    $('#batchUp').addClass("is-invalid");
                    $('#batchUp').after($('<span class="invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('validation.required') + '</strong></span>'));
                } // else if
                else if (err.status == 413) { // if there's no file selected
                    $('#batchUp').addClass("is-invalid");
                    $('#batchUp').after($('<span class="invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('validation.max.file') + '</strong></span>'));
                } // else if
                else if (err.status == 420 || err.status == 500) {
                    console.log(err.responseJSON.message); // test
                    if (err.responseJSON.message === 'Invalid parameters.') {
                        $('#batchUp').addClass("is-invalid");
                        $('#batchUp').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                            Lang.get('fileUploadErrors.Invalid_parameters') + '</strong></span>'));
                    } // if
                    else if (err.responseJSON.message === 'No file sent.') {
                        $('#batchUp').addClass("is-invalid");
                        $('#batchUp').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                            Lang.get('fileUploadErrors.No_file_sent') + '</strong></span>'));
                    } // else if
                    else if (err.responseJSON.message === 'Exceeded filesize limit.') {
                        $('#batchUp').addClass("is-invalid");
                        $('#batchUp').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                            Lang.get('fileUploadErrors.Exceeded_filesize_limit') + '</strong></span>'));
                    } // else if
                    else if (err.responseJSON.message === 'Unknown errors.') {
                        $('#batchUp').addClass("is-invalid");
                        $('#batchUp').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                            Lang.get('fileUploadErrors.Unknown_errors') + '</strong></span>'));
                    } // else if
                    else if (err.responseJSON.message === 'Invalid file format.') {
                        $('#batchUp').addClass("is-invalid");
                        $('#batchUp').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                            Lang.get('fileUploadErrors.Invalid_file_format') + '</strong></span>'));
                    } // else if
                    else if (err.responseJSON.message === 'Failed to move uploaded file.') {
                        $('#batchUp').addClass("is-invalid");
                        $('#batchUp').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' +
                            Lang.get('fileUploadErrors.Failed_to_move_uploaded_file') + '</strong></span>'));
                    } // else if
                    else { // some weird err that im unaware of
                        $('#batchUp').addClass("is-invalid");
                        console.log('some weird error when processing uploaded file : ' + err.responseJSON.message);
                    } // else
                } // else
            } // if error
        }); // end of ajax

    }); // on fileUpForm Submit



    UpdateTempField(); // do it once on page load to make sure it shows correct info

    $('#printBtn').on('click', function (e) {
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

        if (sessionStorage.hasOwnProperty('isnCount')) {
            sendingISNArray = JSON.parse(sessionStorage.getItem('isnArray'));
            sendingISNNameArray = JSON.parse(sessionStorage.getItem('isnName'));
            var isnCount = JSON.parse(sessionStorage.getItem('isnCount'));
            sendingISNSepCount = JSON.parse(sessionStorage.getItem('isnSepCount'));

            console.log(sendingISNNameArray); // test
        } // if

        if (sessionStorage.hasOwnProperty('locCount')) {
            sendingLocArray = JSON.parse(sessionStorage.getItem('locArray'));
            var locCount = JSON.parse(sessionStorage.getItem('locCount'));
            sendingLocSepCount = JSON.parse(sessionStorage.getItem('locSepCount'));
        } // if

        if (sessionStorage.hasOwnProperty('locCount') || sessionStorage.hasOwnProperty('isnCount')) {
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
                    $('body').loadingModal('destroy');
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
        UpdateTempField();
    });

    $("#cleanupISNbtn").on('click', function (e) {
        e.preventDefault();
        // sessionStorage.removeItem("allMats");
        sessionStorage.removeItem("isnArray");
        sessionStorage.removeItem("isnCount");
        sessionStorage.removeItem("isnName");
        sessionStorage.removeItem("isnSepCount");
        $('.barcodePreview.isn').remove();
        // location.reload();
    });

    $("#cleanupLOCbtn").on('click', function (e) {
        e.preventDefault();
        // sessionStorage.removeItem("allMats");
        sessionStorage.removeItem("locArray");
        sessionStorage.removeItem("locCount");
        sessionStorage.removeItem("locSepCount");
        $('.barcodePreview.loc').remove();
        // location.reload();
    });
}); // on document ready

// $(window).on('beforeunload', function() {
//     sessionStorage.clear();
// });
