$(document).ready(function () {
    var tablename = "";
    var hasNameAlready = false;
    var reIsnArray = [];
    var reLocArray = [];
    var reStockArray = [];
    var reCheckArray = [];
    var reCheckArray = [];
    var reTimeArray = [];
    var reProductName = [];
    var reSpecification = [];
    var reUnitPrice = [];
    var reCurrency = [];
    var reUnit = [];
    var reMPQ = [];
    var reMOQ = [];
    var reLT = [];
    var reMonthlyBought = [];
    var reGradeAMats = [];
    var reGPmaterial = [];
    var reAttribution = [];
    var reProvideDepartment = [];
    var reSafeStock = [];
    var uncheckLoc = 0;
    (function () {    // starting set drop down menu on document ready
        if (sessionStorage.getItem('tname') !== null) {
            tablename = JSON.parse(sessionStorage.getItem('tname'));
//            console.log(tablename + "///////") ; // test
            hasNameAlready = true;
        } // if
        else {
            tablename = "No tables found !";
//            console.log(tablename + "///////") ; // test
            document.getElementById('continueT').disabled = true; // 變更欄位為禁用
            hasNameAlready = false;
        } // else 

        var forreal = 0;
        $.ajax({
            url: "createNewTable.php",
            type: "POST",
            data: {forreal: forreal},
            success: function (response) {
                var myObj = JSON.parse(response);
                var wholeMenu = "";
                if (myObj.boolean === true) {
                    if (myObj.data.length > 0) {
                        document.getElementById('continueT').disabled = false; // 變更欄位為啟用
                        if (!hasNameAlready) {
                            tablename = myObj.data[0];
                            sessionStorage.setItem('tname', JSON.stringify(tablename));
                            var arrayNamepiece = myObj.data[0].split('_');
                            $("#continueT").html(arrayNamepiece[1]); // change the button to table name
                            var s = document.getElementById("continueT"); // // change the button value to table name
                            s.value = myObj.data[0];
                        } else {
                            var arrayNamepiece = tablename.split('_');
                            $("#continueT").html(arrayNamepiece[1]); // change the button to table name
                            var s = document.getElementById("continueT"); // // change the button value to table name
                            s.value = tablename;
                        } // else

                        for (let i = 0; i < myObj.data.length; i++) {
                            arrayNamepiece = myObj.data[i].split('_');
                            wholeMenu += '<li><button class="dropdown-item" type="button" value="' + myObj.data[i] + '">' + arrayNamepiece[1] + '</button></li>';
                        } // for
                        $(".dropdown-menu").html(wholeMenu);
                        sendAjaxtoFetch(sortData);
                    } // if
                    else {
                        document.getElementById('continueT').disabled = true; // 變更欄位為禁用
                        $(".dropdown-menu").html('無期間內的盤點表，請建立新的盤點表。');
                    } // else
                } // if
                else {
                    $(".message").html(response);
                } // else
                return false;
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
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
                alert(errorThrown);
            } // error
        }); // ajax

    })();
    function sortData() {
        var SortedLoc = [];
        var SortedIsn = [];
        let reIsnArray1 = JSON.parse(sessionStorage.getItem('isn'));
        let reLocArray1 = JSON.parse(sessionStorage.getItem('loc'));
        let reStockArray1 = JSON.parse(sessionStorage.getItem('stock'));
        let reCheckArray1 = JSON.parse(sessionStorage.getItem('check'));
        let reClientArray1 = JSON.parse(sessionStorage.getItem('client'));
        let reProductName1 = JSON.parse(sessionStorage.getItem('productName'));
        let reSpecification1 = JSON.parse(sessionStorage.getItem('specification'));
        let reUnitPrice1 = JSON.parse(sessionStorage.getItem('unitPrice'));
        let reCurrency1 = JSON.parse(sessionStorage.getItem('currency'));
        let reUnit1 = JSON.parse(sessionStorage.getItem('unit'));
        let reMPQ1 = JSON.parse(sessionStorage.getItem('mpq'));
        let reMOQ1 = JSON.parse(sessionStorage.getItem('moq'));
        let reLT = JSON.parse(sessionStorage.getItem('LT'));
        let reMonthlyBought1 = JSON.parse(sessionStorage.getItem('monthlyBought'));
        let reGradeAMats1 = JSON.parse(sessionStorage.getItem('GradeAMats'));
        let reGPmaterial1 = JSON.parse(sessionStorage.getItem('GPmaterial'));
        let reAttribution1 = JSON.parse(sessionStorage.getItem('attribution'));
        let reProvideDepartment1 = JSON.parse(sessionStorage.getItem('provideDepartment'));
        let reSafeStock1 = JSON.parse(sessionStorage.getItem('safeStock'));
        for (let a = 0; a < reIsnArray1.length; a++) {
            var isIn1 = false;
            var isIn2 = false;
            for (let i = 0; i < SortedLoc.length; i++) { // check if I already put it in
                let $temp = reLocArray1[a];
                if ($temp === SortedLoc[i][0]) {
                    isIn1 = true;
                    break;
                } // if
            } // for

            for (let i = 0; i < SortedIsn.length; i++) { // check if I already put it in
                let $temp = reIsnArray1[a];
                if ($temp === SortedIsn[i][0]) {
                    isIn2 = true;
                    break;
                } // if
            } // for

            if (!isIn1) {
                var innerArray = [];
                innerArray.push(reIsnArray1[a], reStockArray1[a], reClientArray1[a],
                        reProductName1[a], reSpecification1[a], reUnitPrice1[a], reCurrency1[a], reUnit1[a], reMPQ1[a],
                        reMOQ1[a], reLT[a], reMonthlyBought1[a], reGradeAMats1[a], reGPmaterial1[a],
                        reAttribution1[a], reProvideDepartment1[a], reSafeStock1[a]);
                SortedLoc.push([reLocArray1[a], 0, 1, innerArray]); // a two dimentional array  // 儲位, 未盤點, 全部筆數, 底下所有料號明細
                if (reCheckArray1[a] === null) {     // 未盤點的筆數+1
                    let i = SortedLoc.length - 1; // get the latest(newest) data
                    SortedLoc[i][1] = SortedLoc[i][1] + 1;
                } // if
            } // if
            else {
                let $temp = reLocArray1[a];
                for (let i = 0; i < SortedLoc.length; i++) {
                    if ($temp === SortedLoc[i][0]) {
                        SortedLoc[i][2] = SortedLoc[i][2] + 1; // 總共筆數+1
                        if (reCheckArray1[a] === null) { // 未盤點的筆數+1
                            SortedLoc[i][1] = SortedLoc[i][1] + 1;
                            SortedLoc[i][3].push(reIsnArray1[a], reStockArray1[a], reClientArray1[a],
                                    reProductName1[a], reSpecification1[a], reUnitPrice1[a], reCurrency1[a], reUnit1[a], reMPQ1[a],
                                    reMOQ1[a], reLT[a], reMonthlyBought1[a], reGradeAMats1[a], reGPmaterial1[a],
                                    reAttribution1[a], reProvideDepartment1[a], reSafeStock1[a]);
                        } // if
                        break;
                    } // if
                } // for
            } // else

            if (!isIn2) {
                if (reCheckArray1[a] !== null && reCheckArray1[a] !== 0) {
                    var innerArray = [];
                    var total = 1;
                    innerArray.push(reLocArray1[a], reClientArray1[a], reCheckArray1[a]);
                    SortedIsn.push([reIsnArray1[a], total, reProductName1[a], innerArray]); // a two dimentional array
                } // if
            } // if
            else {
                let $temp = reIsnArray1[a]; // right now we got this isn
//                console.log(reCheckArray1[a]); // test
                for (let i = 0; i < SortedIsn.length; i++) {
                    if ($temp === SortedIsn[i][0] && reCheckArray1[a] !== null && reCheckArray1[a] !== 0) {
                        SortedIsn[i][1]++; // 此料號下盤盈虧總共筆數+1
//                        console.log(SortedIsn[i][1] + "(" + SortedIsn[i][0] + ")"); // test
                        SortedIsn[i][3].push(reLocArray1[a], reClientArray1[a], reCheckArray1[a]);
                        break;
                    } // if
                } // for
            } // else 
        } // for

        sessionStorage.setItem('SortedLoc', JSON.stringify(SortedLoc));
        sessionStorage.setItem('SortedIsn', JSON.stringify(SortedIsn));
//        console.log(SortedLoc[0]); // test
        var unchecked = 0;
        for (let j = 0; j < SortedLoc.length; j++) { // 算共有幾個儲位含有未盤點的料號
            if (SortedLoc[j][1] !== 0) {
                unchecked++;
            } // if
        } // for

        var whole = "";
        whole += '<div class="row justify-content-center p-0 m-0">';
        whole += '<div class="col col-auto p-0 m-0">';
        whole += '共' + '<mark><strong class="fs-5" style="color: black">' + SortedLoc.length + '</strong></mark>個儲位，';
        if (unchecked !== 0) {
            whole += '<mark><strong class="fs-2" style="color: red">' + unchecked + '</strong></mark>個儲位未盤';
        } // if
        else {
            whole += '<mark><strong style="color: green">全部儲位盤點完成</strong></mark>';
        } // else

        whole += '</div>';
        whole += '</div>';
        if (unchecked !== 0) {
            whole += '<div class="row justify-content-center">';
            whole += '(以下為前五筆)';
            whole += '</div>';
            // -------------------------------- Loc table ---------------------
            whole += '<div class="table-responsive p-0 m-0">';
            whole += '<table class="table table-warning align-middle">';
            whole += '<thead>';
            whole += '<tr>';
            whole += '<th scope="col col-auto">#</th>';
            whole += '<th scope="col col-auto">儲位</th>';
            whole += '<th scope="col col-auto">未盤/全部</th>';
            whole += '<th scope="col col-auto"></th>';
            whole += '</tr>';
            whole += '</thead>';
            whole += '<tbody>';
            var maxShow = 1;
            for (let j = 0; maxShow < 6 && j < SortedLoc.length; j++) {
                if (SortedLoc[j][1] !== 0) {
                    whole += '<tr>';
                    whole += '<th scope="row" class="col col-auto">' + maxShow + '</th>';
                    whole += '<td class="col col-auto">' + SortedLoc[j][0] + '</td>';
                    whole += '<td class="col col-auto">' + SortedLoc[j][1] + "/" + SortedLoc[j][2] + '</td>';
                    whole += '<td class="col col-auto"><button id="scrollTo' + SortedLoc[j][0] + '" value="dropdown,' + SortedLoc[j][0] + '" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#L' + SortedLoc[j][0] + '" aria-expanded="false" aria-controls="L' + SortedLoc[j][0] + '"><i class="bi bi-caret-down-fill" style="font-size: 1.3em;"></i></button></td>';
                    whole += '<tr>';
                    whole += '<td colspan="4" class="m-0 p-0">';
                    whole += '<div id="L' + SortedLoc[j][0] + '" class="row w-100 p-0 m-0 justify-content-center align-items-center collapse">';
                    whole += '<table class="table table-secondary p-0 m-0">';
                    whole += '<thead>';
                    whole += '<tr>';
                    whole += '<th scope="col" class="col col-auto">料號</th>';
                    whole += '<th scope="col" class="col col-auto">客戶</th>';
                    whole += '<th scope="col" class="col col-auto">品名</th>';
                    whole += '<th scope="col" class="col col-auto">規格</th>';
                    whole += '</tr>';
                    whole += '</thead>';
                    whole += '<tbody>';
                    for (let k = 0; k < SortedLoc[j][3].length; ) {

                        whole += '<tr>';
                        whole += '<td class="col col-auto">' + SortedLoc[j][3][k] + '</td>';
                        whole += '<td class="col col-auto">' + SortedLoc[j][3][k + 2] + '</td>';
                        whole += '<td class="col col-auto">' + SortedLoc[j][3][k + 3] + '</td>';
                        whole += '<td class="col col-auto">' + SortedLoc[j][3][k + 4] + '</td>';
                        whole += '</tr>';

//                        whole += '<div class="col col-auto">';
//                        whole += '<button class="col col-auto btn btn-secondary btn-sm" type="button" value="Detail,' + SortedLoc[j][0] + ',' + SortedLoc[j][3][k] + ',' + SortedLoc[j][3][k + 3] + '">';
//                        whole += '盤點';
//                        whole += '</button>';
//                        whole += '</div>';
                        k = k + 17;
                    } // for

                    whole += '</tbody>';
                    whole += '</table>';
                    whole += '</td>';
                    whole += '</tr>';
                    whole += '</tr>';
                    maxShow++;
                } // if
            } // for
            whole += '</tbody>';
            whole += '</table>';
            whole += '</div>';
        } // if

        $(".dynamicGen").html(whole);
        //--------------------------------------------------------------------------------

        // -------------------------------- Isn table -----------------------------------------
        whole = '';
        whole += '<div class="row justify-content-center">';
        whole += '<div class="col col-auto">';
        whole += '共' + '<mark><strong class="fs-2" style="color: red">' + SortedIsn.length + '</strong></mark>個料號盤盈/虧';
        whole += '</div>';
        whole += '</div>';

        whole += '<div class="table-responsive" style="padding: 0 !important; margin: 0 !important;">';
        whole += '<table class="table table-info align-middle table-striped">';
        whole += '<thead>';
        whole += '<tr>';
        whole += '<th scope="col col-auto p-0 m-0">#</th>';
        whole += '<th scope="col col-auto p-0 m-0">料號</th>';
        whole += '<th scope="col col-auto p-0 m-0">品名</th>';
        whole += '<th scope="col col-auto p-0 m-0">儲位<br>客戶</th>';
        whole += '<th scope="col col-auto p-0 m-0">盈/虧</th>';
        whole += '</tr>';
        whole += '</thead>';
        whole += '<tbody>';

        var rows = 1;
        for (let j = 0; j < SortedIsn.length; j++) {
            for (let k = 0; k < SortedIsn[j][3].length; ) {
                whole += '<tr>';
                if (k === 0) {
                    whole += '<th scope="row" class="col col-auto p-0 m-0">' + rows + '</th>';
                } else {
                    whole += '<th scope="row" class="col col-auto p-0 m-0"></th>';
                } // else

                whole += '<td p-0 m-0>' + SortedIsn[j][0] + '</td>';
                whole += '<td p-0 m-0>' + SortedIsn[j][2] + '</td>';
                whole += '<td>';
                whole += SortedIsn[j][3][k] + '<br><p class="p-0 m-0" style="color: gray; font-size: 0.5rem">';
                whole += SortedIsn[j][3][k + 1];
                whole += '</p></td>';
                whole += '<td><strong style="color: red">';
                if (SortedIsn[j][3][k + 2] > 0) {
                    whole += '+' + SortedIsn[j][3][k + 2];
                } else {
                    whole += SortedIsn[j][3][k + 2];
                } // else 

                whole += '</strong></td>';
                whole += '</tr>';
                whole += '<div id="hidingClass" class="row w-100 p-0 m-0 justify-content-center align-items-center collapse hide">';
                whole += '</div>';
                k = k + 3;
            } // for

            rows++;
        } // for

        whole += '</tbody>';
        whole += '</table>';
        whole += '</div>';
        $(".dynamicGen2").html(whole);
        // ------------------------------------------------------------------
    } // func. sortData

    function sendAjaxtoFetch(sortDataCall) {
        uncheckLoc = "yea we in";
        sessionStorage.clear();
        sessionStorage.setItem('tname', JSON.stringify(tablename));
        $.ajax({
            url: "fetchStatistFromDB.php",
            type: "POST",
            data: {uncheckLoc: uncheckLoc, tname: tablename},
            success: function (response) {
                var myObj = JSON.parse(response);
                if (myObj.boolean === true) {
//                    $(".message").html(response); //test
                    if (myObj.data !== null) {
                        reIsnArray = JSON.parse(JSON.stringify(myObj.data.isn)); // deep copy
                        reLocArray = JSON.parse(JSON.stringify(myObj.data.loc));
                        reStockArray = JSON.parse(JSON.stringify(myObj.data.stock));
                        reCheckArray = JSON.parse(JSON.stringify(myObj.data.isCheck));
                        reTimeArray = JSON.parse(JSON.stringify(myObj.data.updateTime)); // reTimeArray[0].date // the first date
                        reClientArray = JSON.parse(JSON.stringify(myObj.data.client));
                        reProductName = JSON.parse(JSON.stringify(myObj.data.productName));
                        reSpecification = JSON.parse(JSON.stringify(myObj.data.specification));
                        reUnitPrice = JSON.parse(JSON.stringify(myObj.data.unitPrice));
                        reCurrency = JSON.parse(JSON.stringify(myObj.data.currency));
                        reUnit = JSON.parse(JSON.stringify(myObj.data.unit));
                        reMPQ = JSON.parse(JSON.stringify(myObj.data.mpq));
                        reMOQ = JSON.parse(JSON.stringify(myObj.data.moq));
                        reLT = JSON.parse(JSON.stringify(myObj.data.LT));
                        reMonthlyBought = JSON.parse(JSON.stringify(myObj.data.monthlyBought));
                        reGradeAMats = JSON.parse(JSON.stringify(myObj.data.GradeAMats));
                        reGPmaterial = JSON.parse(JSON.stringify(myObj.data.GPmaterial));
                        reAttribution = JSON.parse(JSON.stringify(myObj.data.attribution));
                        reProvideDepartment = JSON.parse(JSON.stringify(myObj.data.provideDepartment));
                        reSafeStock = JSON.parse(JSON.stringify(myObj.data.safeStock));

                        try {
                            sessionStorage.setItem('isn', JSON.stringify(reIsnArray));
                            sessionStorage.setItem('loc', JSON.stringify(reLocArray));
                            sessionStorage.setItem('stock', JSON.stringify(reStockArray));
                            sessionStorage.setItem('check', JSON.stringify(reCheckArray));
                            sessionStorage.setItem('client', JSON.stringify(reClientArray));
                            sessionStorage.setItem('productName', JSON.stringify(reProductName));
                            sessionStorage.setItem('specification', JSON.stringify(reSpecification));
                            sessionStorage.setItem('unitPrice', JSON.stringify(reUnitPrice));
                            sessionStorage.setItem('currency', JSON.stringify(reCurrency));
                            sessionStorage.setItem('unit', JSON.stringify(reUnit));
                            sessionStorage.setItem('mpq', JSON.stringify(reMPQ));
                            sessionStorage.setItem('moq', JSON.stringify(reMOQ));
                            sessionStorage.setItem('LT', JSON.stringify(reLT));
                            sessionStorage.setItem('monthlyBought', JSON.stringify(reMonthlyBought));
                            sessionStorage.setItem('GradeAMats', JSON.stringify(reGradeAMats));
                            sessionStorage.setItem('GPmaterial', JSON.stringify(reGPmaterial));
                            sessionStorage.setItem('attribution', JSON.stringify(reAttribution));
                            sessionStorage.setItem('provideDepartment', JSON.stringify(reProvideDepartment));
                            sessionStorage.setItem('safeStock', JSON.stringify(reSafeStock));
                        } catch (e) {
                            if (e.code === "22" || e.code === "1024") {
                                alert('Quota exceeded!'); //data wasn't successfully saved due to quota exceed so throw an error
                            } // if
                        } // try catch

                        sortDataCall();
                    } // if
                    else {
                        $(".dynamicGen").html('Table has no isn !');
                    } // else
                } // if
                else {
                    $(".message").html('Table has no isn !'); // test
                } // else
                return false;
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
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
                alert(errorThrown);
            } // error
        }); // ajax
    } // func. sendAjaxtoFetch

    $(".dropdownDateMenu").on("click", 'button', function (e) {
        e.preventDefault();
        var s = document.getElementById("continueT");
        s.value = $(this).val();
        tablename = s.value;
        sessionStorage.setItem('tname', JSON.stringify(tablename));
        var Namepiece = $(this).val().split('_');
        $("#continueT").html(Namepiece[1]); // change the button to table name
        $("#continueT").dropdown("toggle"); // close the dropdown menu

        sendAjaxtoFetch(sortData);
        return false;
    });

    $(".dynamicGen").on("click", 'button', function (e) {
        e.preventDefault();
        var code = $(this).val();
        var str = code.split(",");

        if (str[0] === "dropdown") {
//            sessionStorage.clear();
            var tempStr = "#scrollTo" + str[1];
//            console.log( tempStr ) ; // test
            $('html, body').animate({
                scrollTop: $(tempStr).offset().top - 20
            }, 150);
//            sessionStorage.setItem('test', JSON.stringify($(this).parent().parent().next().find("button").val())); // test
        } // if
        else {
            var isIsn = false;
            var s = document.getElementById("continueT");
            tablename = s.value;
//            sessionStorage.clear();
            sessionStorage.setItem('fromConsumMan', JSON.stringify(code));
            sessionStorage.setItem('isIsn', JSON.stringify(isIsn));
            sessionStorage.setItem('tname', JSON.stringify(tablename));
//        window.location.href = 'Search.php';
        } // else

        return false;
    });

    $("#sendTname").on('click', function (e) {
        var code = true;
        var s = document.getElementById("continueT");
        tablename = s.value;
        sessionStorage.setItem('fromConsumMan', JSON.stringify(code));
        sessionStorage.setItem('tname', JSON.stringify(tablename));
        window.location.href = 'Search.php';
    });

    $("#outputExcelbtn").on('click', function (e) {
        var s = document.getElementById("continueT");
        tablename = s.value;
        var arrayNamepiece = tablename.split('_');

        var actualFileName = arrayNamepiece[1] + ".xlsx";
        let sortedISN = JSON.parse(sessionStorage.getItem('SortedIsn'));
//        console.log( sortedISN.length ) ; // test 
        $.ajax({
            url: "writeExcelFile.php",
            type: "POST",
            data: {actualFileName: actualFileName, sortedArray: JSON.stringify(sortedISN)},
            success: function (response) {
                var myObj = JSON.parse(response);
                var wholeMenu = "";
                if (myObj.boolean === true) {
                    var temp = '<p class="col col-auto" style="color: green ;">';
                    temp = temp + myObj.message + "</p>";
                    $(".errorOutPut").html(temp);
//                    $(".errorOutPut").append(response); // test
                    //creating an invisible element
                    var element = document.createElement('a');
                    element.setAttribute('href', 'download/' + actualFileName );
                    element.setAttribute('download', actualFileName);

                    // Above code is equivalent to
                    // <a href="path of file" download="file name">

                    document.body.appendChild(element);

                    //click it and download by script
                    element.click();

                    document.body.removeChild(element); // delete element
                } // if
                else {
                    var temp = '<p class="col col-auto" style="color: red ;">';
                    temp = temp + myObj.message + "</p>";
                    $(".errorOutPut").html(myObj.message);
//                    $(".errorOutPut").append(response); // test
                } // else
                return false;
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
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
                alert(errorThrown);
            } // error
        }); // ajax
    });
}); // on document ready 