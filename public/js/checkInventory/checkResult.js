$(document).ready(function () {
    var serialSheetsObj = {};

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

    $("#inp").on('submit', function (e) {
        e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();
        var $temp = $('#texBox').val();
        $('#texBox').val(''); // clear input box value
        $isIsn = document.getElementById('toggle-state').checked;
        $isLoc = !$isIsn;
        var $timeRange = $('#DateRangeString').text();
        var $formatStr = moment().localeData().longDateFormat('L');
        $.ajax({
            type: "post",
            url: "/checking/checkInentdbSearchTimeRange",
            data: { texBox: $temp, isIsn: $isIsn, timeRange: $timeRange, formatStr: $formatStr },
            dataType: 'json', // expected respose datatype from server
            success: function (response) {
                var myObjs = JSON.parse(JSON.stringify(response.data));
                serialSheetsObj = {};
                for (let a = 0; a < myObjs.length; a++) {
                    if( serialSheetsObj.hasOwnProperty(myObjs[a].單號) ){
                        var tempObj = {};
                        tempObj["料號"] = myObjs[a].料號;
                        tempObj["現有庫存"] = myObjs[a].現有庫存;
                        tempObj["儲位"] = myObjs[a].儲位;
                        tempObj["客戶別"] = myObjs[a].客戶別;
                        tempObj["盤點"] = myObjs[a].盤點;
                        tempObj["updated_by"] = myObjs[a].updated_by;
                        tempObj["姓名"] = myObjs[a].姓名;
                        tempObj["created_at"] = myObjs[a].created_at;
                        tempObj["updated_at"] = myObjs[a].updated_at;
                        tempObj["品名"] = myObjs[a].品名;

                        // 沒有要顯示的部份 要顯示再打開就好 都有從db抓出來了
                        // tempObj["規格"] = myObjs[a].規格;
                        // tempObj["單價"] = myObjs[a].單價;
                        // tempObj["幣別"] = myObjs[a].幣別;
                        // tempObj["單位"] = myObjs[a].單位;
                        // tempObj["MPQ"] = myObjs[a].MPQ;
                        // tempObj["MOQ"] = myObjs[a].MOQ;
                        // tempObj["LT"] = myObjs[a].LT;
                        // tempObj["月請購"] = myObjs[a].月請購;
                        // tempObj["A級資材"] = myObjs[a].A級資材;
                        // tempObj["耗材歸屬"] = myObjs[a].耗材歸屬;
                        // tempObj["發料部門"] = myObjs[a].發料部門;
                        // tempObj["安全庫存"] = myObjs[a].安全庫存;

                        serialSheetsObj[myObjs[a].單號].push(tempObj);
                    } // if
                    else {
                        serialSheetsObj[myObjs[a].單號] = [] ;

                        var tempObj = {};
                        tempObj["料號"] = myObjs[a].料號;
                        tempObj["現有庫存"] = myObjs[a].現有庫存;
                        tempObj["儲位"] = myObjs[a].儲位;
                        tempObj["客戶別"] = myObjs[a].客戶別;
                        tempObj["盤點"] = myObjs[a].盤點;
                        tempObj["updated_by"] = myObjs[a].updated_by;
                        tempObj["姓名"] = myObjs[a].姓名;
                        tempObj["created_at"] = myObjs[a].created_at;
                        tempObj["updated_at"] = myObjs[a].updated_at;
                        tempObj["品名"] = myObjs[a].品名;

                        // 沒有要顯示的部份 要顯示再打開就好 都有從db抓出來了
                        // tempObj["規格"] = myObjs[a].規格;
                        // tempObj["單價"] = myObjs[a].單價;
                        // tempObj["幣別"] = myObjs[a].幣別;
                        // tempObj["單位"] = myObjs[a].單位;
                        // tempObj["MPQ"] = myObjs[a].MPQ;
                        // tempObj["MOQ"] = myObjs[a].MOQ;
                        // tempObj["LT"] = myObjs[a].LT;
                        // tempObj["月請購"] = myObjs[a].月請購;
                        // tempObj["A級資材"] = myObjs[a].A級資材;
                        // tempObj["耗材歸屬"] = myObjs[a].耗材歸屬;
                        // tempObj["發料部門"] = myObjs[a].發料部門;
                        // tempObj["安全庫存"] = myObjs[a].安全庫存;

                        serialSheetsObj[myObjs[a].單號].push(tempObj);
                    } // else
                } // for

                console.log(serialSheetsObj); // test
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
                else if (err.status == 420) { // else if error 420
                    $('#texBox').addClass("is-invalid");
                    if ($isIsn) {
                        $('#texBox').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_isn') + '</strong></span>'));
                    } else {
                        $('#texBox').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_loc') + '</strong></span>'));
                    } // else
                } // else 
                else {
                    // Lang = new Lang();
                    console.log(err.status); // test
                } // else
            } // error
        }); // ajax
    }); // on submit

    // date range picker function
    $(function () {
        // var start = moment().subtract(29, 'days');
        var start = moment().subtract(3, 'month').startOf('month');
        var end = moment().subtract(0, 'month').endOf('month');

        function cb(start, end) {
            $('#reportrange span').html(start.format(moment().localeData().longDateFormat('L')) + ' ～ ' + end.format(moment().localeData().longDateFormat('L')));
        }

        var all_history_records = Lang.get('templateWords.all_history_records');
        var past_three_months = Lang.get('templateWords.past_three_months');
        var tody = Lang.get('templateWords.today');
        var json_rangeObj = {};
        json_rangeObj[tody] = [moment(), moment()];
        json_rangeObj[past_three_months] = [moment().subtract(3, 'month').startOf('month'), moment().subtract(0, 'month').endOf('month')];
        json_rangeObj[all_history_records] = [moment().subtract(99, 'year').startOf('year'), moment().subtract(0, 'month').endOf('month')];

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: json_rangeObj
        }, cb);

        cb(start, end);
    });


}); // on document ready