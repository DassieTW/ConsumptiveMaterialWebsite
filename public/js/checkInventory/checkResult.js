$(document).ready(function () {
    var serialSheetsObj = {}; // objs of array of objs
    var allcreators = []; // array of objs
    var sheetCreators = []; // array of objs

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
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        } // if else

        if ($(this).hasClass('active')) {
            if ($(this).hasClass('sortUp') && $(this).hasClass('sortBtn')) {
                $(this).removeClass('sortUp');
                $(this).addClass('sortDw');

                $(this).find('i').removeClass('bi-sort-up');
                $(this).find('i').addClass('bi-sort-down-alt');

            } // if
            else if ($(this).hasClass('sortDw') && $(this).hasClass('sortBtn')) {
                $(this).removeClass('sortDw');
                $(this).addClass('sortUp');

                $(this).find('i').removeClass('bi-sort-down-alt');
                $(this).find('i').addClass('bi-sort-up');
            } // else if

            if ($(this).attr('name') === 'sortLocBtn') {
                collapseByLoc();
                $(".filterBtn").removeClass("active");
                $(".filterBtn2").removeClass("active");
                $("#texBox").val(""); // clear textbox input
                if( $("#toggle-state").is(":checked") ) {
                    $("#toggle-state").prop('checked', false); // switch checkbox to checked
                    $("#toggle-state").trigger("change"); // trigger the change event
                } // if
                
                if ($("#sortISNBtn").hasClass("active") && $("#sortISNBtn").hasClass("sortUp")) {
                    sortTable("asc", "isnTable");
                } // if
                else if ($("#sortISNBtn").hasClass("active") && $("#sortISNBtn").hasClass("sortDw")) {
                    sortTable("dec", "isnTable");
                } // else if

                if ($(this).hasClass('sortUp')) {
                    sortTable("asc", "locTable");
                } // if
                else if ($(this).hasClass('sortDw')) {
                    sortTable("dec", "locTable");
                } // if
            } // if
            else if ($(this).attr('name') === "sortISNBtn" && $(this).hasClass('sortUp')) {
                sortTable("asc", "isnTable");
            } // else if
            else if ($(this).attr('name') === "sortISNBtn" && $(this).hasClass('sortDw')) {
                sortTable("dec", "isnTable");
            } // else if
        } else {
            if ($(this).attr('name') === 'sortLocBtn') {
                plainISNTable();
                $(".filterBtn").removeClass("active");
                $(".filterBtn2").removeClass("active");
                $("#texBox").val(""); // clear textbox input
                if( ! $("#toggle-state").is(":checked") ) {
                    $("#toggle-state").prop('checked', true); // switch checkbox to checked
                    $("#toggle-state").trigger("change"); // trigger the change event
                } // if

                if ($("#sortISNBtn").hasClass("active") && $("#sortISNBtn").hasClass("sortUp")) {
                    sortTable("asc", "isnTable");
                    // console.log('sortTable("asc", "isnTable")'); // test
                } // if
                else if ($("#sortISNBtn").hasClass("active") && $("#sortISNBtn").hasClass("sortDw")) {
                    sortTable("dec", "isnTable");
                    // console.log('sortTable("dec", "isnTable")'); // test
                } // else if
            } // if
        } // if else   
    }); // sortBtn on click

    $(".filterBtn").on("click", function (e) {
        e.preventDefault();

        // zzz = 10 ; // test
        // console.log(zzz); // test
        // let zzz ; // test
        // console.log(zzz); // test

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        } // if else

        if ($(this).hasClass('active')) { // enabling a filter
            if ($(this).attr('name') === 'sortCheckedBtn') {
                filterTable("filter_checked");
            } // if
            else if ($(this).attr('name') === "sortNotCheckedBtn") {
                filterTable("filter_not_checked");
            } // else if
        } else { // disabling a filter
            filterTable("show_all"); // revert the filters first
            if ($('#sortCheckedBtn').hasClass("active")) {
                filterTable("filter_checked");
            } // if

            if ($('#sortNotCheckedBtn').hasClass("active")) {
                filterTable("filter_not_checked");
            } // if

            if ($('#sortNotRightBtn').hasClass("active")) {
                filterTable("filter_not_right");
            } // if
        } // if else   
    }); // filterBtn on click

    $(".filterBtn2").on("click", function (e) {
        e.preventDefault();
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        } // if else

        if ($(this).hasClass('active')) { // enabling a filter
            if ($(this).attr('name') === 'sortNotRightBtn') {
                filterTable("filter_not_right");
            } // if
        } else { // disabling a filter
            filterTable("show_all"); // revert the filters first
            if ($('#sortCheckedBtn').hasClass("active")) {
                filterTable("filter_checked");
            } // if

            if ($('#sortNotCheckedBtn').hasClass("active")) {
                filterTable("filter_not_checked");
            } // if

            if ($('#sortNotRightBtn').hasClass("active")) {
                filterTable("filter_not_right");
            } // if
        } // if else   
    }); // filterBtn2 on click

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

    // ---------------- quick sort --------------

    var swap = function (data, data2, i, j) {
        var tmp = data[i];
        data[i] = data[j];
        data[j] = tmp;

        if (data2 != null && data2 != undefined) {
            var tmp2 = data2[i];
            data2[i] = data2[j];
            data2[j] = tmp2;
        } // if

    }; // swap

    var partition = function (data, data2, left, right, dir) {
        var pivot = $(data[right]).find('span').text();
        // console.log(pivot); // test
        var i = left - 1;
        for (let j = left; j < right; j++) {
            if (dir === "asc") {
                if ($(data[j]).find('span').text() < pivot) {
                    i++;
                    swap(data, data2, i, j);
                } // if
            } // if sort to ascending
            else {
                if ($(data[j]).find('span').text() > pivot) {
                    i++;
                    swap(data, data2, i, j);
                } // if
            } // else sort to decending
        } // for
        i++;
        swap(data, data2, i, right);
        return i;
    }; // partition

    var quickSort = function (data, data2, left, right, dir) { // data2 is only for moving row corresponding to data
        if (left < right) {
            var pivot = partition(data, data2, left, right, dir);
            quickSort(data, data2, left, pivot - 1, dir);    // 對左子串列進行快速排序
            quickSort(data, data2, pivot + 1, right, dir);   // 對右子串列進行快速排序
        } // if left < right
    }; // quickSort

    // ------------------------ end of quick sort ---------------- // 

    function sortTable(dir, tableClass) {
        $("." + tableClass).each(function (index, obj) {
            var table = $(this);

            // console.log(table.parent().attr("id")); // test
            if (tableClass === "locTable") { // if it is a Loc Table
                var rows = table.find('tr.locRows').toArray();
                var rowsData = [];
                $.each(rows, function (i, item) {
                    rowsData.push($(this).next()[0]); // push the corresponding isn data rows
                    // next() get the dom as jquery object, while adding[0] gets the pure DOM element
                });
                // console.log(rows); // test
                quickSort(rows, rowsData, 0, rows.length - 1, dir);
                // console.log(rows); // test
                // console.log(rowsData); // test

                $.each(rows, function (i, item) {
                    table.append(this);
                    table.append(rowsData[i]);
                });
                // console.log($(this).find("tr").toArray()); // test
            } // if
            else { // if it is an isn Table
                var rows = table.find('tr.isnRows').toArray();

                // console.log(rows); // test
                quickSort(rows, null, 0, rows.length - 1, dir);
                // console.log(rows); // test

                $.each(rows, function (i, item) {
                    $(this).children(":first").text((i + 1) + ".");
                    table.append(this);
                }); // each
            } // else
        }); // each
    } // sortTable

    function filterTable(filterType) {
        if (filterType === "filter_checked") {

            $(".isnTable").each(function (index, obj) {
                var table = $(this);
                var rows = table.find('tr.isnRows').toArray();

                $.each(rows, function (i, item) {
                    $(this).children(":first").text((i + 1) + ".");
                    let tempStr = "";
                    if ($("#sortLocBtn").hasClass("active")) { // if collapse by loc
                        tempStr = $(this).children().eq(3).html();
                    } // if
                    else {
                        tempStr = $(this).children().eq(4).html();
                    } // else
                    let resultArr = tempStr.split('<hr class="m-0 p-0">');
                    // console.log(resultArr); // test
                    if (resultArr[1] === "N/A" && (!$(this).hasClass("d-none"))) {
                        $(this).addClass("d-none"); // hide the row
                    } // if

                    table.append(this);
                }); // each
            }); // each
        } // if
        else if (filterType === "filter_not_checked") {
            $(".isnTable").each(function (index, obj) {
                var table = $(this);
                var rows = table.find('tr.isnRows').toArray();

                $.each(rows, function (i, item) {
                    $(this).children(":first").text((i + 1) + ".");
                    let tempStr = "";
                    if ($("#sortLocBtn").hasClass("active")) { // if collapse by loc
                        tempStr = $(this).children().eq(3).html();
                    } // if
                    else {
                        tempStr = $(this).children().eq(4).html();
                    } // else
                    let resultArr = tempStr.split('<hr class="m-0 p-0">');
                    if (resultArr[1] !== "N/A" && (!$(this).hasClass("d-none"))) {
                        $(this).addClass("d-none"); // hide the row
                    } // if

                    table.append(this);
                }); // each
            }); // each
        }  // else if
        else if (filterType === "filter_not_right") {
            $(".isnTable").each(function (index, obj) {
                var table = $(this);
                var rows = table.find('tr.isnRows').toArray();

                $.each(rows, function (i, item) {
                    $(this).children(":first").text((i + 1) + ".");
                    let tempStr = "";
                    if ($("#sortLocBtn").hasClass("active")) { // if collapse by loc
                        tempStr = $(this).children().eq(3).html();
                    } // if
                    else {
                        tempStr = $(this).children().eq(4).html();
                    } // else
                    let resultArr = tempStr.split('<hr class="m-0 p-0">');
                    if (resultArr[1] !== "N/A" && parseInt(resultArr[0], 10) === parseInt(resultArr[1], 10) && (!$(this).hasClass("d-none"))) {
                        $(this).addClass("d-none"); // hide the row
                    } // if

                    table.append(this);
                }); // each
            }); // each
        } // else if
        else { // filter none, show all rows
            $(".isnTable").each(function (index, obj) {
                var table = $(this);
                var rows = table.find('tr.isnRows').toArray();

                // console.log(rows); // test

                $.each(rows, function (i, item) {
                    $(this).children(":first").text((i + 1) + ".");
                    $(this).removeClass("d-none"); // show the rows
                    table.append(this);
                }); // each
                // console.log($(this).find("tr").toArray()); // test
            }); // each
        } // else
    } // filterTable

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
        $("#appendDataHere").html(""); // clear the table
        const keys = Object.keys(serialSheetsObj);
        // console.log(keys); // test
        var sheetCount = 0;
        for (let b = 0; b < keys.length; b++) { // loop thru sheets
            if (!keys[b].includes('_byLoc')) {
                var serialNumDataRow = $('<tr>', { "data-bs-toggle": "collapse", "data-bs-target": "#sheet" + b, "aria-expanded": "false" }); // create an elemet by jquery
                
                let dateStr = serialSheetsObj[keys[b]][0].created_at.split(" ") ; // split the string to date string and time string
                let dateCreated = moment(dateStr[0], "YYYY-MM-DD"); // parse the string to date
                let thisSeasonStartingDate = moment().subtract(3, 'months').startOf('month');
                if( dateCreated.isBefore(thisSeasonStartingDate) ) { // then this is a old record that shoudnt be changed
                    serialNumDataRow.append('<td style="color: gray;">' + keys[b] + ' <a href="/checking?sheet=' + keys[b] + '"><i class="bi bi-clipboard-plus" style="color: blue; font-size: 1.2em;"></i></a>' + "</td>");
                    serialNumDataRow.append('<td style="color: gray;">' + sheetCreators[sheetCount] + "</td>");
                    serialNumDataRow.append('<td style="color: gray;">' + serialSheetsObj[keys[b]][0].created_at + "</td>");
                } // if
                else { // in season records
                    serialNumDataRow.append("<td>" + keys[b] + ' <a href="/checking?sheet=' + keys[b] + '"><i class="bi bi-clipboard-plus" style="color: blue; font-size: 1.2em;"></i></a>' + "</td>");
                    serialNumDataRow.append("<td>" + sheetCreators[sheetCount] + "</td>");
                    serialNumDataRow.append("<td>" + serialSheetsObj[keys[b]][0].created_at + "</td>");
                } // else
                
                serialNumDataRow.append('<td><a class="collapseBtn" disabled style="color: grey;"><i class="bi bi-chevron-down"></i></a></td>');
                var detailDataRowWithCollapse = $('<tr>', {});
                var detailTD = $('<td>', { "colspan": "12", "class": "p-0 m-0" });
                var locCollapseDiv = $('<div>', { "class": "collapse p-0 m-0", "id": "sheet" + b, "aria-expanded": "false" });
                var locTable = $('<table>', { "class": "table table-primary table-hover align-items-center table-responsive m-0 p-0 locTable" });
                var thead = $('<thead>', { "class": "table table-primary table-hover m-0 p-0" });

                var tbody = $('<tbody>', {});
                for (let c = 0; c < serialSheetsObj[keys[b] + "_byLoc"].length; c++) { // loop thru byLoc arrays
                    var locName = Object.keys(serialSheetsObj[keys[b] + "_byLoc"][c])[0];
                    var dataTR = $('<tr>', { "class": "locRows", "data-bs-toggle": "collapse", "data-bs-target": "#locData" + keys[b].substring(0, keys[b].length - 9) + "_" + c, "aria-expanded": "false" });
                    dataTR.append("<td>&nbsp;</td>");

                    // console.log(locName); // test
                    dataTR.append('<td><span class="locTD">' + Object.keys(serialSheetsObj[keys[b] + "_byLoc"][c])[0] + " </span>" + '<a href="/checking?sheet=' + keys[b] + '&loc=' + Object.keys(serialSheetsObj[keys[b] + "_byLoc"][c])[0] + '"><i class="bi bi-clipboard-plus" style="color: blue; font-size: 1.2em;"></i></a>' + "</td>");
                    dataTR.append("<td>" + serialSheetsObj[keys[b] + "_byLoc"][c][locName + "Check"] + "/" + serialSheetsObj[keys[b] + "_byLoc"][c][locName + "All"] + "</td>");
                    dataTR.append('<td><a class="collapseBtn" disabled style="color: grey;"><i class="bi bi-chevron-down"></i></a></td>');
                    tbody.append(dataTR);

                    var isnTR = $('<tr>', {});
                    var isnTD = $('<td>', { "colspan": "12", "class": "p-0 m-0" });
                    var isnCollapseDiv = $('<div>', { "class": "collapse p-0 m-0", "id": "locData" + keys[b].substring(0, keys[b].length - 9) + "_" + c, "aria-expanded": "false" });
                    var isnTable = $('<table>', { "class": "table table-success table-hover table-responsive align-items-center m-0 p-0 isnTable" });
                    var isnthead = $('<thead>', {});
                    var tr0 = $('<tr>', { "class": "align-items-center", "style": "vertical-align: middle;" });
                    tr0.append("<th>#</th>");
                    tr0.append('<th class="col col-3">' + Lang.get('checkInvLang.isn') + '<hr class="m-0 p-0">' + Lang.get('checkInvLang.product_name') + "</th>");
                    tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.client') + "</th>");
                    tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.stock') + '<hr class="m-0 p-0">' + Lang.get('checkInvLang.checking_result') + "</th>");
                    tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.updated_by') + "</th>");
                    tr0.append('<th class="col col-3">' + Lang.get('checkInvLang.updated_at') + "</th>");
                    tr0.append('<th>&nbsp;</th>');
                    isnthead.append(tr0);
                    isnTable.append(isnthead);

                    var isntbody = $('<tbody>', {});
                    for (let n = 0; n < serialSheetsObj[keys[b] + "_byLoc"][c][locName].length; n++) { // loop thru isn under the cth byLoc arrays
                        var isnnTR = $('<tr class="align-items-center isnRows" style="vertical-align: middle;">', {});
                        isnnTR.append('<td>' + (n + 1) + "." + '</td>');
                        isnnTR.append('<td><span class="isnTD">' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].料號 + '</span><hr class="m-0 p-0">' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].品名 + '</td>');
                        isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].客戶別 + '</td>');
                        if (serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 === "" || serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 === null || serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 === "null") {
                            isnnTR.append('<td class="table-danger table-hover" style="color: red;">' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].現有庫存 + '<hr class="m-0 p-0">' + Lang.get('checkInvLang.unknown') + '</td>');
                            isnnTR.append('<td>' + Lang.get('checkInvLang.unknown') + '</td>');
                            isnnTR.append('<td>' + Lang.get('checkInvLang.unknown') + '</td>');
                        } // if
                        else {
                            if (serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].現有庫存 !== serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 ) {
                                isnnTR.append('<td class="table-danger table-hover" style="color: red;">' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].現有庫存 + '<hr class="m-0 p-0">' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 + '</td>');
                            } else {
                                isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].現有庫存 + '<hr class="m-0 p-0">' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].盤點 + '</td>');
                            } // if else

                            isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].姓名 + '</td>');
                            isnnTR.append('<td>' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].updated_at + '</td>');

                        } // else
                        isnnTR.append('<td>' + '<a href="/checking?sheet=' + keys[b] + '&loc=' + Object.keys(serialSheetsObj[keys[b] + "_byLoc"][c])[0] + '&isn=' + serialSheetsObj[keys[b] + "_byLoc"][c][locName][n].料號 + '"><i class="bi bi-clipboard-plus" style="color: blue; font-size: 1.2em;"></i></a>' + '</td>');

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

    function plainISNTable() {
        $("#appendDataHere").html(""); // clear the table
        const keys = Object.keys(serialSheetsObj);
        var sheetCount = 0;

        for (let b = 0; b < keys.length; b++) { // loop thru sheets
            if (!keys[b].includes('_byLoc')) {
                var serialNumDataRow = $('<tr>', { "data-bs-toggle": "collapse", "data-bs-target": "#sheet" + b, "aria-expanded": "false" }); // create an elemet by jquery
                
                let dateStr = serialSheetsObj[keys[b]][0].created_at.split(" ") ; // split the string to date string and time string
                let dateCreated = moment(dateStr[0], "YYYY-MM-DD"); // parse the string to date
                let thisSeasonStartingDate = moment().subtract(3, 'months').startOf('month');
                if( dateCreated.isBefore(thisSeasonStartingDate) ) { // then this is a old record that shoudnt be changed
                    serialNumDataRow.append('<td style="color: gray;">' + keys[b] + ' <a href="/checking?sheet=' + keys[b] + '"><i class="bi bi-clipboard-plus" style="color: blue; font-size: 1.2em;"></i></a>' + "</td>");
                    serialNumDataRow.append('<td style="color: gray;">' + sheetCreators[sheetCount] + "</td>");
                    serialNumDataRow.append('<td style="color: gray;">' + serialSheetsObj[keys[b]][0].created_at + "</td>");
                } // if
                else { // in season records
                    serialNumDataRow.append("<td>" + keys[b] + ' <a href="/checking?sheet=' + keys[b] + '"><i class="bi bi-clipboard-plus" style="color: blue; font-size: 1.2em;"></i></a>' + "</td>");
                    serialNumDataRow.append("<td>" + sheetCreators[sheetCount] + "</td>");
                    serialNumDataRow.append("<td>" + serialSheetsObj[keys[b]][0].created_at + "</td>");
                } // else
                
                serialNumDataRow.append('<td><a class="collapseBtn" disabled style="color: grey;"><i class="bi bi-chevron-down"></i></a></td>');
                var plainISNCollapseDiv = $('<div>', { "class": "collapse p-0 m-0", "id": "sheet" + b, "aria-expanded": "false" });

                var isnTR = $('<tr>', {});
                var isnTD = $('<td>', { "colspan": "12", "class": "p-0 m-0" });

                var isnTable = $('<table>', { "class": "table table-success table-hover table-responsive align-items-center m-0 p-0 isnTable" });
                var isnthead = $('<thead>', {});
                var tr0 = $('<tr>', { "class": "align-items-center", "style": "vertical-align: middle;" });
                tr0.append("<th>#</th>");
                tr0.append('<th class="col col-3">' + Lang.get('checkInvLang.isn') + '<hr class="m-0 p-0">' + Lang.get('checkInvLang.product_name') + "</th>");
                tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.client') + "</th>");
                tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.loc') + "</th>");
                tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.stock') + '<hr class="m-0 p-0">' + Lang.get('checkInvLang.checking_result') + "</th>");
                tr0.append('<th class="col col-2">' + Lang.get('checkInvLang.updated_by') + "</th>");
                tr0.append('<th class="col col-3">' + Lang.get('checkInvLang.updated_at') + "</th>");
                tr0.append('<th>&nbsp;</th>');
                isnthead.append(tr0);
                isnTable.append(isnthead);

                var isntbody = $('<tbody>', {});
                for (let n = 0; n < serialSheetsObj[keys[b]].length; n++) {
                    var isnnTR = $('<tr class="align-items-center isnRows" style="vertical-align: middle;">', {});
                    isnnTR.append('<td>' + (n + 1) + "." + '</td>');
                    isnnTR.append('<td><span class="isnTD">' + serialSheetsObj[keys[b]][n].料號 + '</span><hr class="m-0 p-0">' + serialSheetsObj[keys[b]][n].品名 + '</td>');
                    isnnTR.append('<td>' + serialSheetsObj[keys[b]][n].客戶別 + '</td>');
                    isnnTR.append('<td>' + serialSheetsObj[keys[b]][n].儲位 + '</td>');
                    if (serialSheetsObj[keys[b]][n].盤點 === "" || serialSheetsObj[keys[b]][n].盤點 === null || serialSheetsObj[keys[b]][n].盤點 === "null") {
                        isnnTR.append('<td class="table-danger table-hover" style="color: red;">' + serialSheetsObj[keys[b]][n].現有庫存 + '<hr class="m-0 p-0">' + Lang.get('checkInvLang.unknown') + '</td>');
                        isnnTR.append('<td>' + Lang.get('checkInvLang.unknown') + '</td>');
                        isnnTR.append('<td>' + Lang.get('checkInvLang.unknown') + '</td>');
                    } // if
                    else {
                        if (parseInt(serialSheetsObj[keys[b]][n].現有庫存, 10) !== parseInt(serialSheetsObj[keys[b]][n].盤點, 10)) {
                            isnnTR.append('<td class="table-danger table-hover" style="color: red;">' + serialSheetsObj[keys[b]][n].現有庫存 + '<hr class="m-0 p-0">' + serialSheetsObj[keys[b]][n].盤點 + '</td>');
                        } else {
                            isnnTR.append('<td>' + serialSheetsObj[keys[b]][n].現有庫存 + '<hr class="m-0 p-0">' + serialSheetsObj[keys[b]][n].盤點 + '</td>');
                        } // if else

                        isnnTR.append('<td>' + serialSheetsObj[keys[b]][n].姓名 + '</td>');
                        isnnTR.append('<td>' + serialSheetsObj[keys[b]][n].updated_at + '</td>');

                    } // else
                    isnnTR.append('<td>' + '<a href="/checking?sheet=' + keys[b] + '&loc=' + serialSheetsObj[keys[b]][n].儲位 + '&isn=' + serialSheetsObj[keys[b]][n].料號 + '"><i class="bi bi-clipboard-plus" style="color: blue; font-size: 1.2em;"></i></a>' + '</td>');

                    isntbody.append(isnnTR);
                } // for

                isnTable.append(isntbody);
                plainISNCollapseDiv.append(isnTable);
                isnTD.append(plainISNCollapseDiv);
                isnTR.append(isnTD);

                $("#appendDataHere").append(serialNumDataRow);
                $("#appendDataHere").append(isnTR);
            } // if
            else {
                sheetCount++;
            } // else
        } // for

        // sortTable("dec", "isnTable");
    } // plainISNTable

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

                // console.log(serialSheetsObj); // test
                collapseByLoc();
                if ($("#sortLocBtn").hasClass("sortUp")) {
                    sortTable("asc", "locTable");
                } // if
                else {
                    sortTable("dec", "locTable");
                } // else if

                $("#sortISNBtn").removeClass("active"); // deactivate the isn button 
                $(".filterBtn").removeClass("active"); // deactivate all filter buttons
                $("#sortLocBtn").addClass("active"); // activate loc button as default

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
                else if (err.status == 420) { // else if error : No results Found !
                    $('#texBox').addClass("is-invalid");

                    // if ($isIsn) {
                    //     // $('#texBox').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_isn') + '</strong></span>'));
                    // } else {
                    //     // $('#texBox').after($('<span class="col col-auto invalid-feedback p-0 m-0" role="alert"><strong>' + Lang.get('checkInvLang.no_such_loc') + '</strong></span>'));
                    // } // else
                    $("#appendDataHere").html(""); // clear the table
                    console.log(err.responseJSON.message); // test
                } // else 
                else {
                    console.log(err.status); // test
                    console.log(err.responseJSON.message); // test
                } // else
            } // error
        }); // ajax


    });

}); // on document ready