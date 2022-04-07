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
            var isnSpec = JSON.parse(sessionStorage.getItem('isnSpec'));
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
                isnSpec.splice(index1, 1);
                isnCount = isnCount - 1;
                sessionStorage.setItem('isnArray', JSON.stringify(isnArray));
                sessionStorage.setItem('isnName', JSON.stringify(isnName));
                sessionStorage.setItem('isnCount', JSON.stringify(isnCount));
                sessionStorage.setItem('isnSpec', JSON.stringify(isnSpec));
                sessionStorage.setItem('isnSepCount', JSON.stringify(isnSepCount));
            } // if
        } // if isnArray is set

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
        var isnSpec = JSON.parse(sessionStorage.getItem('isnSpec'));
        var isnSepCount = JSON.parse(sessionStorage.getItem('isnSepCount'));

        var strr = $(this).attr('id');
        const myArr = strr.split("__");
        // console.log(myArr); // test
        // console.log(myArr.length); // test
        let index2 = -1;
        $.grep(isnArray, function (isn, index) {
            // console.log(isnName[index]); // test
            if (isn === myArr[0] && isnName[index] === myArr[1] && isnSpec[index] === myArr[2]) {
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
} // appenSVg

function UpdateTempField() {
    // clean up previous
    $('.barcodePreview').remove();
    var isnArray = JSON.parse(sessionStorage.getItem('isnArray'));
    var isnName = JSON.parse(sessionStorage.getItem('isnName'));
    var isnCount = JSON.parse(sessionStorage.getItem('isnCount'));
    var isnSpec = JSON.parse(sessionStorage.getItem('isnSpec'));
    var isnSepCount = JSON.parse(sessionStorage.getItem('isnSepCount'));

    for (let ty = 0; ty < isnCount; ty++) {
        $('#tableHead').after($('<tr class="barcodePreview align-items-center" id="' + isnArray[ty] + '__' + isnName[ty] + '__' + isnSpec[ty] + '">' +
            '<td class="col col-auto align-items-center px-0 m-0"><a class="deleteBtn" id="' + isnArray[ty] + '__' + isnName[ty] + '__' + isnSpec[ty] +'" value="' + isnArray[ty] + '__' + isnName[ty] + '__' + isnSpec[ty] + '">' +
            '</a></td>' +
            '<td class="col col-auto align-items-center px-0 m-0"><span>' +
            isnArray[ty] +
            '</span><br><span>' +
            isnName[ty] +
            '</span></td>' +
            // '<td class="col col-auto align-items-center px-0 m-0"><span>' + isnSpec[ty] + '</span></td>' +
            '<td class="col col-auto align-items-center px-0 m-0">'+
            '<input inputmode="numeric" type="number" min="0" id="' + isnArray[ty] + '__' + isnName[ty] + '__' + isnSpec[ty] + '" class="printNum" name="printNum" style="width: 6ch;"value="' +
            isnSepCount[ty] + '">'
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
        sessionStorage.clear();
        localStorage.clear();
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        $.ajax({
            type: "post",
            url: "/barcode/search_isn",
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
                $('body').loadingModal('destroy');
            },
            success: function (response) {
                var myObjs = JSON.parse(JSON.stringify(response.data));
                var isnSheet = [];
                var nameSheet = [];
                var specSheet = [];
                for (let a = 0; a < myObjs.length; a++) {
                    isnSheet.push(myObjs[a].料號);
                    nameSheet.push(myObjs[a].品名);
                    specSheet.push(myObjs[a].規格);
                } // for

                var isnCount = 0;
                var isnResult = [];
                var nameResult = [];
                var specResult = [];
                var isnSepCountResult = [];

                var isnSepCount = 1;
                for (let a = 0; a < isnSheet.length; a++) {
                    for (let b = a + 1; b < isnSheet.length; b++) {
                        if (isnSheet[a] === isnSheet[b] && nameSheet[a] === nameSheet[b] &&
                            specSheet[a] === specSheet[b]) {
                            isnSepCount++;
                            isnSheet.splice(b, 1);
                            nameSheet.splice(b, 1);
                            specSheet.splice(b, 1);
                        } // if
                    } // for

                    isnCount++;
                    isnResult.push(isnSheet[a]);
                    if (nameSheet[a] === null) {
                        nameResult.push("");
                    } else {
                        nameResult.push(nameSheet[a]);
                    } // else

                    if (specSheet[a] === null) {
                        specResult.push("");
                    } else {
                        specResult.push(specSheet[a]);
                    } // else

                    isnSepCountResult.push(isnSepCount);
                    isnSepCount = 1;
                } // for

                sessionStorage.setItem('isnCount', JSON.stringify(isnCount));
                sessionStorage.setItem('isnArray', JSON.stringify(isnResult));
                sessionStorage.setItem('isnName', JSON.stringify(nameResult));
                sessionStorage.setItem('isnSpec', JSON.stringify(specResult));
                sessionStorage.setItem('isnSepCount', JSON.stringify(isnSepCountResult));

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
                    $('#searchIn').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_isn') + '</strong></span>'));
                } // else if
                else {
                    // Lang = new Lang();
                    console.log(err.status); // test
                } // else
            }
        }); // end of ajax
    }); // on isnForm submit

    sessionStorage.clear();
    UpdateTempField(); // do it once on page load to make sure it shows correct info

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

        if (sessionStorage.hasOwnProperty('isnCount')) {
            sendingISNArray = JSON.parse(sessionStorage.getItem('isnArray'));
            sendingISNNameArray = JSON.parse(sessionStorage.getItem('isnName'));
            var isnCount = JSON.parse(sessionStorage.getItem('isnCount'));
            sendingISNSepCount = JSON.parse(sessionStorage.getItem('isnSepCount'));

            console.log(sendingISNNameArray); // test
        } // if

        if (sessionStorage.hasOwnProperty('isnCount')) {
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
    });

    $("#cleanupISNbtn").on('click', function (e) {
        e.preventDefault();
        // sessionStorage.removeItem("allMats");
        sessionStorage.removeItem("isnArray");
        sessionStorage.removeItem("isnCount");
        sessionStorage.removeItem("isnName");
        sessionStorage.removeItem("isnSepCount");
        sessionStorage.removeItem("isnSpec");
        $('.barcodePreview').remove();
        // location.reload();
    });
}); // on document ready

$(window).on('beforeunload', function() {
    sessionStorage.clear();
});