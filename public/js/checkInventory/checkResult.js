$(document).ready(function () {
    var serialSheetsObj = {}; // objs of array of objs
    var allcreators = []; // array of objs
    var sheetCreators = []; // array of objs
    var serialSheetsObjbyLoc = {};
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "post",
        url: "/checking/get_creators",
        dataType: 'json', // expected respose datatype from server
        success: function (response) {
            allcreators = JSON.parse(JSON.stringify(response.data));
            // console.log(allcreators); // test
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

                // you can loop through the errors object and show it to the user
                // console.warn(err.responseJSON.errors); // test
                // display errors on each form field
                $.each(err.responseJSON.errors, function (i, error) {
                    console.log(error[0]); // show errors in console only 
                });
            } // if error 422
            else if (err.status == 420) { // else if error 420
                console.log(err); // show errors in console only 
            } // else 
            else {
                console.log(err.status); // test
            } // else
        } // error
    }); // ajax

    function quickSearch() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("texBox");
        var isISN = $("#toggle-state").is(":checked");
        // console.log(isISN); // test
        filter = input.value.toUpperCase();
        if (input.value === "" || input.value === null) {
            $('.isnRows').each(function (i, obj) {
                obj.style.display = "";
            });
            $('.locRows').each(function (i, obj) {
                obj.style.display = "";
            });
        } else {
            // Loop through all table rows, and hide those who don't match the search query
            if (isISN) {
                $('.isnRows').each(function (i, obj) {
                    txtValue = $(this).find("span.isnTD").text();
                    // console.log("now checking text : " + txtValue); // test
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        obj.style.display = "";
                    } else {
                        obj.style.display = "none";
                    } // if else
                });
            } // if
            else {
                $('.locRows').each(function (i, obj) {
                    txtValue = $(this).find("span.locTD").text();
                    // console.log("now checking text : " + txtValue); // test
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        obj.style.display = "";
                    } else {
                        obj.style.display = "none";
                        $(this).next().find("div").removeClass("show");
                    } // if else
                });
            } // else
        } // if else

    } // quickSearch function

    $(".sortBtn").on("click", function (e) {
        e.preventDefault();

    });

    $(".sortBtn").on('mousedown touchstart', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        } // if else

        // var clickedElementText = $(this).find('span').text();
        // $(".sortBtn").each(function (index, element) {
        //     // element == this
        //     if ($(element).find('span').text() === clickedElementText) {
        //         // skip it
        //     } // if
        //     else {
        //         if ($(element).hasClass('active')) {
        //             $(element).removeClass('active');
        //         } // if
        //     } // if else
        // });
    });

    $(".sortBtn").on('mouseup touchend', function () {
        if ($(this).hasClass('active')) {
            if ($(this).hasClass('sortUp') && $(this).hasClass('sortBtn')) {
                $(this).removeClass('sortUp');
                $(this).addClass('sortDw');

                $(this).find('i').removeClass('bi-sort-up');
                $(this).find('i').addClass('bi-sort-down-alt');
            } // if
            else if ($(this).hasClass('sortBtn')) {
                $(this).removeClass('sortDw');
                $(this).addClass('sortUp');

                $(this).find('i').removeClass('bi-sort-down-alt');
                $(this).find('i').addClass('bi-sort-up');
            } // else
        } else {
        } // if else    
    });

    $(".filterBtn").on('mousedown touchstart', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        } // if else
    });

    $(".filterBtn2").on('mousedown touchstart', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        } // if else
    });

    (function () { // starting show on document ready
        if (document.getElementById("toggle-state").checked) {
            $('#toggle-state-text').text(Lang.get('checkInvLang.isn'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.isn'));
        } // if
        else {
            $('#toggle-state-text').text(Lang.get('checkInvLang.loc_short'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.loc_short'));
        } // else

        // $("#texBox").focus();
    })();

    $('#toggle-state').on('change', function () { // 目標改變
        // 'this' will contain a reference to the checkbox
        if (this.checked) {
            $('#toggle-state-text').text(Lang.get('checkInvLang.isn'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.isn'));
        } else {
            $('#toggle-state-text').text(Lang.get('checkInvLang.loc_short'));
            document.getElementById("texBox").setAttribute("placeholder", Lang.get('checkInvLang.loc_short'));
        } // if else

        $("#texBox").focus();
    });  // 目標改變

    $("#inp").on('submit', function (e) {
        e.preventDefault();

    }); // on submit

    // date range picker function
    $(function () {
        // var start = moment().subtract(29, 'days');
        var start = moment().subtract(3, 'month').startOf('month');
        var end = moment().subtract(0, 'month').endOf('month');

        function cb(start, end) {
            $('#reportrange span').html(start.format(moment().localeData().longDateFormat('L')) + ' ～ ' + end.format(moment().localeData().longDateFormat('L')));
            $("#DateRangeString").trigger('change');
        }

        var all_history_records = Lang.get('templateWords.all_history_records');
        var past_three_months = Lang.get('templateWords.past_three_months');
        var tody = Lang.get('templateWords.today');
        var json_rangeObj = {};
        json_rangeObj[tody] = [moment().startOf('day'), moment().endOf('day')];
        json_rangeObj[past_three_months] = [moment().subtract(3, 'month').startOf('month'), moment().subtract(0, 'month').endOf('month')];
        json_rangeObj[all_history_records] = [moment().subtract(99, 'year').startOf('year'), moment().subtract(0, 'month').endOf('month')];

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: json_rangeObj
        }, cb);

        cb(start, end);
    });

    $("#texBox").on('input', function (e) {
        e.preventDefault();
        quickSearch();
    });

    function compare(a, b) {
        if (a.last_nom < b.last_nom) {
            return -1;
        } // if
        if (a.last_nom > b.last_nom) {
            return 1;
        } // if
        return 0;
    } // compare


    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("myTable2");
        switching = true;
        // Set the sorting direction to ascending:
        dir = "asc";
        /* Make a loop that will continue until
        no switching has been done: */
        while (switching) {
            // Start by saying: no switching is done:
            switching = false;
            rows = table.rows;
            /* Loop through all table rows (except the
            first, which contains table headers): */
            for (i = 1; i < (rows.length - 1); i++) {
                // Start by saying there should be no switching:
                shouldSwitch = false;
                /* Get the two elements you want to compare,
                one from current row and one from the next: */
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                /* Check if the two rows should switch place,
                based on the direction, asc or desc: */
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        // If so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                /* If a switch has been marked, make the switch
                and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                // Each time a switch is done, increase this count by 1:
                switchcount++;
            } else {
                /* If no switching has been done AND the direction is "asc",
                set the direction to "desc" and run the while loop again. */
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                } // if
            } // if else
        } // while
    } // sort table

    function getName(id) {
        var name = "";
        for (let a = 0; a < allcreators.length; a++) {
            if (id === allcreators[a].username) {
                name = allcreators[a].姓名;
            } // if
        } // for

        return name;
    } // find the creator name by id

    function collapseByLoc() {
        // console.log(serialSheetsObj); // test
        // console.log(sheetCreators); // test
        // $("#appendDataHere").html(""); // clear the table
        const keys = Object.keys(serialSheetsObj);
        // console.log(keys); // test
        var sheetCount = 0;
        for (let b = 0; b < keys.length; b++) {
            if (!keys[b].includes('_byLoc')) {
                var serialNumDataRow = $('<tr>', { "data-bs-toggle": "collapse", "data-bs-target": "#sheet" + b, "aria-expanded": "false" }); // create an elemet by jquery
                serialNumDataRow.append("<td>" + keys[b] + "</td>");
                serialNumDataRow.append("<td>" + sheetCreators[sheetCount] + "</td>");
                serialNumDataRow.append("<td>" + serialSheetsObj[keys[b]][0].created_at + "</td>");
                serialNumDataRow.append('<td><a class="collapseBtn" disabled style="color: grey;"><i class="bi bi-chevron-down"></i></a></td>');
                var detailDataRowWithCollapse = $('<tr>', {});
                var detailTD = $('<td>', { "colspan": "12", "class": "p-0 m-0" });
                var locCollapseDiv = $('<div>', { "class": "collapse p-0 m-0", "id": "sheet" + b, "aria-expanded": "false" });
                var locTable = $('<table>', { "class": "table table-primary table-hover align-items-center table-responsive m-0 p-0" });
                var thead = $('<thead>', { "class": "table table-primary table-hover m-0 p-0" });

                var tbody = $('<tbody>', {});
                for (let c = 0; c < serialSheetsObj[keys[b] + "_byLoc"].length; c++) {
                    var locName = Object.keys(serialSheetsObj[keys[b] + "_byLoc"][c])[0];
                    var dataTR = $('<tr>', { "class": "locRows", "data-bs-toggle": "collapse", "data-bs-target": "#locData" + keys[b].substring(0, keys[b].length - 9) + "_" + c, "aria-expanded": "false" });
                    dataTR.append("<td>&nbsp;</td>");

                    // console.log(locName); // test
                    dataTR.append('<td><span class="locTD">' + Object.keys(serialSheetsObj[keys[b] + "_byLoc"][c])[0] + "</td>");
                    dataTR.append("<td>" + serialSheetsObj[keys[b] + "_byLoc"][c][locName + "Check"] + "/" + serialSheetsObj[keys[b] + "_byLoc"][c][locName + "All"] + "</td>");
                    dataTR.append('<td><a class="collapseBtn" disabled style="color: grey;"><i class="bi bi-chevron-down"></i></a></td>');
                    tbody.append(dataTR);

                    var isnTR = $('<tr>', {});
                    var isnTD = $('<td>', { "colspan": "12", "class": "p-0 m-0" });
                    var isnCollapseDiv = $('<div>', { "class": "collapse p-0 m-0", "id": "locData" + keys[b].substring(0, keys[b].length - 9) + "_" + c, "aria-expanded": "false" });
                    var isnTable = $('<table>', { "class": "table table-success table-hover table-responsive align-items-center m-0 p-0" });
                    var isnthead = $('<thead>', {});
                    var tr0 = $('<tr>', { "class": "align-items-center", "style": "vertical-align: middle;" });
                    tr0.append("<th>#</th>");
                    tr0.append('<th class="col col-3">' + Lang.get('checkInvLang.isn') + "<br>" + Lang.get('checkInvLang.product_name') + "</th>");
                    tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.client') + "</th>");
                    tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.stock') + "<br>" + Lang.get('checkInvLang.checking_result') + "</th>");
                    tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.updated_by') + "</th>");
                    tr0.append('<th class="col col-3">' + Lang.get('checkInvLang.updated_at') + "</th>");
                    tr0.append('<th>&nbsp;</th>');
                    isnthead.append(tr0);
                    isnTable.append(isnthead);

                    var isntbody = $('<tbody>', {});
                    for (let n = 0; n < serialSheetsObj[keys[b] + "_byLoc"][c][locName].length; n++) {
                        var isnnTR = $('<tr class="align-items-center isnRows" style="vertical-align: middle;">', {});
                        isnnTR.append('<td>' + (n + 1) + "." + '</td>');
                        isnnTR.append('<td><span class="isnTD">' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].料號 + '</span><br>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].品名 + '</td>');
                        isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].客戶別 + '</td>');
                        if (serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 === "" || serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 === null || serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 === "null") {
                            isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].現有庫存 + "<br>" + Lang.get('checkInvLang.unknown') + '</td>');
                            isnnTR.append('<td>' + Lang.get('checkInvLang.unknown') + '</td>');
                            isnnTR.append('<td>' + Lang.get('checkInvLang.unknown') + '</td>');
                        } // if
                        else {
                            isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].現有庫存 + "<br>" + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 + '</td>');
                            isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].姓名 + '</td>');
                            isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].updated_at + '</td>');

                        } // else
                        isnnTR.append('<td>' + '<button class="btn btn-success">GO</button>' + '</td>');

                        isntbody.append(isnnTR);
                    } // for

                    isnTable.append(isntbody);
                    isnCollapseDiv.append(isnTable);
                    isnTD.append(isnCollapseDiv);
                    isnTR.append(isnTD);

                    tbody.append(isnTR);

                } // for

                var tr = $('<tr>', {});
                tr.append("<th>&nbsp;</th>");
                tr.append("<th>" + Lang.get('checkInvLang.loc_short') + "</th>");
                tr.append("<th>" + Lang.get('checkInvLang.checked') + "/" + Lang.get('checkInvLang.all') + "</th>");
                tr.append("<th>&nbsp;</th>");
                thead.append(tr);
                locTable.append(thead);
                locTable.append(tbody);
                locCollapseDiv.append(locTable);
                detailTD.append(locCollapseDiv);
                detailDataRowWithCollapse.append(detailTD);

                $("#appendDataHere").append(serialNumDataRow);
                $("#appendDataHere").append(detailDataRowWithCollapse);
            } // if
            else {
                sheetCount++;
            } // else
        } // for

    } // collapseByLoc

    $("#DateRangeString").on('change', function () { // trigger ajax post whenever time range is set
        // console.log("time range triggered"); // test
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();
        sheetCreators = []; // array of objs

        var $temp = "";
        // $('#texBox').val(''); // clear input box value
        // $isIsn = document.getElementById('toggle-state').checked;
        // $isLoc = !$isIsn;
        var $timeRange = $('#DateRangeString').text();
        var $formatStr = moment().localeData().longDateFormat('L');
        $.ajax({
            type: "post",
            url: "/checking/checkInentdbSearchTimeRange",
            data: { timeRange: $timeRange, formatStr: $formatStr },
            dataType: 'json', // expected respose datatype from server
            success: function (response) {
                var myObjs = JSON.parse(JSON.stringify(response.data));
                // console.log(myObjs); // test
                serialSheetsObj = {};
                for (let a = 0; a < myObjs.length; a++) {
                    if (serialSheetsObj.hasOwnProperty(myObjs[a].單號)) {
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
                        // console.log( "existing serial's isn : " + tempObj["料號"] ); // test
                        for (let c = 0; c < serialSheetsObj[myObjs[a].單號].length;) {
                            // console.log( tempObj["料號"] + " compare to " + serialSheetsObj[myObjs[a].單號][c]["料號"]); // test

                            if (tempObj["料號"] <= serialSheetsObj[myObjs[a].單號][c]["料號"]) {
                                serialSheetsObj[myObjs[a].單號].splice(c, 0, tempObj);
                                break;
                            } // if

                            c = c + 1;

                            if (c >= serialSheetsObj[myObjs[a].單號].length) { // the current isn is the largest
                                serialSheetsObj[myObjs[a].單號].push(tempObj);
                                break;  // since the serialSheetsObj[myObjs[a].單號].length increased, so the loop will run one more time,
                                // break to prevent the excess loop
                            } // if

                        } // for

                        for (let z = 0; z < serialSheetsObj[myObjs[a].單號 + "_byLoc"].length;) {
                            if (tempObj["儲位"] === Object.keys(serialSheetsObj[myObjs[a].單號 + "_byLoc"][z])[0]) { // Loc already existed
                                serialSheetsObj[myObjs[a].單號 + "_byLoc"][z][tempObj["儲位"]].push(tempObj);
                                if (tempObj["盤點"] === null) {
                                    serialSheetsObj[myObjs[a].單號 + "_byLoc"][z][tempObj["儲位"] + "All"] += 1;
                                } // if
                                else {
                                    serialSheetsObj[myObjs[a].單號 + "_byLoc"][z][tempObj["儲位"] + "Check"] += 1;
                                    serialSheetsObj[myObjs[a].單號 + "_byLoc"][z][tempObj["儲位"] + "All"] += 1;
                                } // else
                                break;
                            } // if

                            z = z + 1;

                            if (z >= serialSheetsObj[myObjs[a].單號 + "_byLoc"].length) { // the loc is a new one
                                var tempObjLoc = {};
                                tempObjLoc[myObjs[a].儲位] = []; // a list of isn thats under this location

                                tempObjLoc[myObjs[a].儲位].push(tempObj);
                                if (tempObj["盤點"] === null) {
                                    tempObjLoc[myObjs[a].儲位 + "Check"] = 0;
                                    tempObjLoc[myObjs[a].儲位 + "All"] = 1;
                                } // if
                                else {
                                    tempObjLoc[myObjs[a].儲位 + "Check"] = 1;
                                    tempObjLoc[myObjs[a].儲位 + "All"] = 1;
                                } // else

                                serialSheetsObj[myObjs[a].單號 + "_byLoc"].push(tempObjLoc);

                                break;  // since the length increased, so the loop will run one more time,
                                // break to prevent the excess loop
                            } // if
                        } // for
                    } // if  單號 already existed
                    else { // if 單號 not exist

                        serialSheetsObj[myObjs[a].單號] = [];
                        let tempStr = myObjs[a].單號 + "_byLoc";
                        serialSheetsObj[tempStr] = [];
                        let username = myObjs[a].單號.split("_")[0];
                        // console.log( username ) ; // test
                        sheetCreators.push(getName(username));
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

                        // console.log( "new serial's isn : " + tempObj["料號"] ); // test

                        var tempObjLoc = {};
                        tempObjLoc[myObjs[a].儲位] = []; // a list of isn thats under this location

                        tempObjLoc[myObjs[a].儲位].push(tempObj);
                        if (tempObj["盤點"] === null) {
                            tempObjLoc[myObjs[a].儲位 + "Check"] = 0;
                            tempObjLoc[myObjs[a].儲位 + "All"] = 1;
                        } // if
                        else {
                            tempObjLoc[myObjs[a].儲位 + "Check"] = 1;
                            tempObjLoc[myObjs[a].儲位 + "All"] = 1;
                        } // else

                        serialSheetsObj[tempStr].push(tempObjLoc);
                        serialSheetsObj[myObjs[a].單號].push(tempObj);

                    } // else
                } // for

                console.log(serialSheetsObj); // test
                collapseByLoc();

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
                        // $('#texBox').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_isn') + '</strong></span>'));
                    } else {
                        // $('#texBox').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_loc') + '</strong></span>'));
                    } // else
                } // else 
                else {
                    console.log(err.status); // test
                } // else
            } // error
        }); // ajax


    });

}); // on document ready