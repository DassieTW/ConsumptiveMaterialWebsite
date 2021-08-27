$(document).ready(function () {
    var RowsPerPage = 2;
    var tablename = "";
    var reIsnArray = [];
    var reLocArray = [];
    var reStockArray = [];
    var reCheckArray = [];
    var reTimeArray = [];
    var totalSlides = 0;
    var $NotYetChecked = 0;
    var $isIsn = false;
    var $isLoc = false;
    function arrowChangeToUp() {
        var element;
        element = document.getElementById("showHideBtn");
        element.classList.add('animated', 'rubberBand');
        setTimeout(function () {
            element.classList.remove('rubberBand');
        }, 500);
    } // func. arrowChangeToUp

    function arrowChangeToDown() {
        var element;
        element = document.getElementById("showHideBtn");
        element.classList.add('animated', 'rubberBand');
        setTimeout(function () {
            element.classList.remove('rubberBand');
        }, 500);
    } // func. arrowChangeToDown

    function post(path, params, method = 'post') { // submit in a traditoinal way( redirect to the php )
        // The rest of this code assumes you are not using a library.
        // It can be made less wordy if you use one.
        const form = document.createElement('form');
        form.method = method;
        form.action = path;
        for (const key in params) {
            if (params.hasOwnProperty(key)) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = params[key];
                form.appendChild(hiddenField);
            }
        } // for

        document.body.appendChild(form);
        form.submit();
    } // post

    (function () { // starting show on document ready
        if (document.getElementById("toggle-state").checked) {
            document.getElementById("texBox").setAttribute("placeholder", "輸入 料號條碼");
        } // if
        else {
            document.getElementById("texBox").setAttribute("placeholder", "輸入 儲位條碼");
        } // else
    })();

    $('#toggle-state').change(function () { // 目標改變
        // this will contain a reference to the checkbox
        if (this.checked) {
            document.getElementById("texBox").setAttribute("placeholder", "輸入 料號條碼");
        } else {
            document.getElementById("texBox").setAttribute("placeholder", "輸入 儲位條碼");
        } // if else

        $("#texBox").focus();
    });  // 目標改變

    function FromConsumManOrHomePage() { // check if coming from ConsumMan or HomePage ( called immediately after SetUpDropDownMenu)
        if (sessionStorage.getItem("fromHomePage") === null && sessionStorage.getItem("fromConsumMan") === null) {
            // do nothing
        } // if
        else if (sessionStorage.getItem("fromHomePage") !== null) {
            var temp = JSON.parse(sessionStorage.getItem('fromHomePage'));
            var tablename3 = JSON.parse(sessionStorage.getItem('tname'));
            var fakeName = tablename3.split('_');
            $("#continueT").html(fakeName[1]); // change the button to table name
            var s = document.getElementById("continueT"); // // change the button value to table name
            s.value = tablename3;

//            console.log("name: " + tablename3);// test
        } // else if
        else if (sessionStorage.getItem("fromConsumMan") !== null) {
            var temp = JSON.parse(sessionStorage.getItem('fromConsumMan'));
//            document.getElementById("texBox").value = temp;
//            var isIt = JSON.parse(sessionStorage.getItem('isIsn'));
            var tablename2 = JSON.parse(sessionStorage.getItem('tname'));

            var fakeName = tablename2.split('_');
            $("#continueT").html(fakeName[1]); // change the button to table name
            var s = document.getElementById("continueT"); // // change the button value to table name
            s.value = tablename2;

//            console.log("name: " + temp + "  isIsn?  " + isIt + " table name : " + s.value);// test

//            if (isIt === "true") {
//                $('#toggle-state').bootstrapToggle('on');
//            } // if
//            else {
//                $('#toggle-state').bootstrapToggle('off');
//            } // else

//            console.log("check box: " + document.getElementById("toggle-state").checked ) ;// test
//            setTimeout(function () {
//                $('#inp').submit();
//            }, 100);
        } // else if
        else {
            console.log("No one was directed!");
        } // else

//        sessionStorage.clear(); // clean from the start
    } // check if coming from ConsumMan

    (function SetUpDropDownMenu() {    // starting set drop down menu on document ready
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
//                        document.getElementById('continueT').disabled = false; // 變更欄位為啟用
                        tablename = myObj.data[0];
                        var arrayNamepiece = myObj.data[0].split('_');
                        wholeMenu += '<li><button class="dropdown-item" type="button" value="' + myObj.data[0] + '">' + arrayNamepiece[1] + '</button></li>';
                        $("#continueT").html(arrayNamepiece[1]); // change the button to table name
                        var s = document.getElementById("continueT"); // // change the button value to table name
                        s.value = myObj.data[0];
//                        for (let i = 1; i < myObj.data.length; i++) {
//                            arrayNamepiece = myObj.data[i].split('_');
//                            wholeMenu += '<li><button class="dropdown-item" type="button" value="' + myObj.data[i] + '">' + arrayNamepiece[1] + '</button></li>';
//                        } // for
//                        $(".dropdown-menu").html(wholeMenu);
                        FromConsumManOrHomePage();   // set up finish, call to check if coming from ConsumMan, if so, submit
                    } // if
                    else {
//                        document.getElementById('continueT').disabled = false; // 變更欄位為啟用
//                        $(".dropdown-menu").html('無期間內的盤點表，請建立新的盤點表。');
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
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
                alert(errorThrown);
            } // error
        });    // ajax
    })(); // SetUpDropDownMenu

    var tempAll = []; // a two dimentional array for temp scan input
    var tempIsn = [];
    var tempLoc = [];

    $("#inp").on('submit', function (e) {
        //不要自己抓input了 用button讓他們決定是要掃料號or儲位
        e.preventDefault();
        totalSlides = 0;
        $NotYetChecked = 0;
        $(".message").html(''); // cleanup
        $(".carousel-inner").html(''); // cleanup
        $('.collapse').collapse("hide"); // hide
        // -------------------------------------- for single condition search ---------------------------- //
        var $temp = $('#texBox').val();
        $('#texBox').val(''); // clear input box value
        $isIsn = document.getElementById('toggle-state').checked;
        $isLoc = !$isIsn;
        // -------------------------------------- end------------------------------------------------------------- //

// -------------------------------------- for multiple condition search ---------------------------- //
//        var $temp = $('#texBox').val();
//        $isIsn = false;
//        $isLoc = false;
//        $('#texBox').val(''); // clear input box value
//        $isIsn = document.getElementById('toggle-state').checked;
//        $isLoc = !$isIsn;
//        if ($isLoc) {
//            var isAlready = false;
//            for (let i = 0; i < tempAll.length; i++) {
//                if ($temp.localeCompare(tempAll[i][0]) === 0) {
//                    isAlready = true;
//                } // if
//            } // for
//            if (isAlready === false) {
//                tempAll.push([$temp.toString(), 'loc']);  // a two dimentional array
//                tempLoc.push($temp.toString());
//            } // if
//
//        } else if ($isIsn) {
//            var isAlready = false;
//            for (let i = 0; i < tempAll.length; i++) { // check if already in the showing list
//                if ($temp.localeCompare(tempAll[i][0]) === 0) {
//                    isAlready = true;
//                } // if
//            } // for
//            if (isAlready === false) {
//                tempAll.push([$temp.toString(), 'isn']);  // a two dimentional array
//                tempIsn.push($temp.toString());
//            } // if
//        } // else
//
//
//        // analize the list
//        var re;
//        var s1 = 'loc';
//        var s2 = 'isn';
//        var locCount = 0;
//        var isnCount = 0;
//        var quForISN = "(";
//        var quForLOC = "(";
//        for (let i = 0; i < tempAll.length; i++) { // check if already in the showing list
//            if (s1.localeCompare(tempAll[i][1]) === 0) {
//                locCount++;
//            } // if
//            else {
//                isnCount++;
//            } // if else
//        } // for
//
//        var listLocCount = 0;
//        var listIsnCount = 0;
//        for (let i = 0; i < tempAll.length; i++) { // check if already in the showing list
//            if (s1.localeCompare(tempAll[i][1]) === 0) {
//                quForLOC += ("'" + tempAll[i][0] + "'");
//                listLocCount++;
//                if (listLocCount < locCount) {
//                    quForLOC += ", ";
//                } // if
//            } // if
//            else {
//                quForISN += ("'" + tempAll[i][0] + "'");
//                listIsnCount++;
//                if (listIsnCount < isnCount) {
//                    quForISN += ", ";
//                } // if
//            } // if else
//        } // for
//
//        quForISN += ")";
//        quForLOC += ")";
//        var wholeOptions = "";
//        if (tempAll[0][1] === 'isn') { // check the first element
//            if (tempIsn.length > 0) {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">這些料號 :</strong></div>';
//            } // if
//
//            for (let i = 0; i < tempIsn.length; i++) {
//                wholeOptions += '\
//                           <div class="col-auto">\n\
//                              <div class="input-group rounded-pill align-items-center" style="padding: 0px; border: 1px solid darkgray;">\n\
//                                     <input type="text" name="isn" class="form-control rounded-pill" readonly\n\
//                                       style="text-align: center; width: 15ch; padding: 1px; border: 0px;"\n\
//                                       value="' + tempIsn[i] + '">\n\
//                                       <button type="button" class="btn btn-sm rounded-pill p-0 m-0" value="' + tempIsn[i] + '">\n\
//                                         <i class="bi-x" style="color: red; vertical-align: middle;"></i></div>\n\
//                                       </button>\n\
//                                 </div>\n\
//                            </div>';
//            } // for
//
//            if (tempLoc.length > 0) {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">在&nbsp;&nbsp;儲位 :</strong></div>';
//            } // if
//
//            for (let i = 0; i < tempLoc.length; i++) {
//                wholeOptions += '\
//                           <div class="col-auto">\n\
//                              <div class="input-group rounded-pill align-items-center" style="padding: 0px; border: 1px solid darkgray;">\n\
//                                     <input type="text" name="isn" class="form-control rounded-pill" readonly\n\
//                                       style="text-align: center; width: 10ch; padding: 1px; border: 0px;"\n\
//                                       value="' + tempLoc[i] + '">\n\
//                                       <button type="button" class="btn btn-sm rounded-pill p-0 m-0" value="' + tempLoc[i] + '">\n\
//                                         <i class="bi-x" style="color: red; vertical-align: middle;"></i></div>\n\
//                                       </button>\n\
//                              </div>\n\
//                            </div>';
//            } // for
//
//            if (tempLoc.length === 0) {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">在所有儲位之狀態 :</strong></div>';
//            } else {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">之狀態 :</strong></div>';
//            } // if else
//
//
//        } else { // tempAll[0][1] == 'loc'
//            if (tempLoc.length > 0) {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">這些儲位中 :</strong></div>';
//            } // if
//
//            for (let i = 0; i < tempLoc.length; i++) {
//                wholeOptions += '\
//                           <div class="col-auto">\n\
//                              <div class="input-group rounded-pill align-items-center" style="padding: 0px; border: 1px solid darkgray;">\n\
//                                     <input type="text" name="isn" class="form-control rounded-pill" readonly\n\
//                                       style="text-align: center; width: 10ch; padding: 1px; border: 0px;"\n\
//                                       value="' + tempLoc[i] + '">\n\
//                                       <button type="button" class="btn btn-sm rounded-pill p-0 m-0" value="' + tempLoc[i] + '">\n\
//                                         <i class="bi-x" style="color: red; vertical-align: middle;"></i></div>\n\
//                                       </button>\n\
//                              </div>\n\
//                            </div>';
//            } // for
//
//            if (tempIsn.length > 0) {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">料號 :</strong></div>';
//            } // if
//
//            for (let i = 0; i < tempIsn.length; i++) {
//                wholeOptions += '\
//                           <div class="col-auto">\n\
//                              <div class="input-group rounded-pill align-items-center" style="padding: 0px; border: 1px solid darkgray;">\n\
//                                     <input type="text" name="isn" class="form-control rounded-pill" readonly\n\
//                                       style="text-align: center; width: 15ch; padding: 1px; border: 0px;"\n\
//                                       value="' + tempIsn[i] + '">\n\
//                                       <button type="button" class="btn btn-sm rounded-pill p-0 m-0" value="' + tempIsn[i] + '">\n\
//                                         <i class="bi-x" style="color: red; vertical-align: middle;"></i></div>\n\
//                                       </button>\n\
//                                 </div>\n\
//                            </div>';
//            } // for
//
//            if (tempIsn.length === 0) {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">所有料號之狀態 :</strong></div>';
//            } else {
//                wholeOptions += '<div class="col-auto"><strong style="text-align: center;">之狀態 :</strong></div>';
//
//            } // if else
//        } // if else
//        $(".options").html(wholeOptions);
// -------------------------------------- end ----------------------------------------------------------- //
        tablename = $('#continueT').val();
        reIsnArray = [];
        reLocArray = [];
        reStockArray = [];
        reCheckArray = [];
        reTimeArray = [];
        reClientArray = [];

//--------------------------------------   single conditions --------------------------------- //
        $.ajax({
            url: "dbSearch.php",
            type: "POST",
//            async: false,
            data: {onlyCode: $temp, isIsn: $isIsn, tablename: tablename},
            success: function (response) {
                var myObj = JSON.parse(response);
//                console.log("httt" + response); // test
                if (myObj.data === null) {
                    $(".message").append("No Results Found !");
                } else {
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

                    // quick sort
                    var swap = function (data, i, j) {
//                        console.log(data); // test
                        var tmp = data[i];
                        data[i] = data[j];
                        data[j] = tmp;
//                        console.log(data); // test

                        tmp = reIsnArray[i];
                        reIsnArray[i] = reIsnArray[j];
                        reIsnArray[j] = tmp;

                        tmp = reLocArray[i];
                        reLocArray[i] = reLocArray[j];
                        reLocArray[j] = tmp;

                        tmp = reStockArray[i];
                        reStockArray[i] = reStockArray[j];
                        reStockArray[j] = tmp;

                        tmp = reTimeArray[i];
                        reTimeArray[i] = reTimeArray[j];
                        reTimeArray[j] = tmp;

                        tmp = reClientArray[i];
                        reClientArray[i] = reClientArray[j];
                        reClientArray[j] = tmp;

                        tmp = reProductName[i];
                        reProductName[i] = reProductName[j];
                        reProductName[j] = tmp;

                        tmp = reSpecification[i];
                        reSpecification[i] = reSpecification[j];
                        reSpecification[j] = tmp;

                        tmp = reUnitPrice[i];
                        reUnitPrice[i] = reUnitPrice[j];
                        reUnitPrice[j] = tmp;

                        tmp = reCurrency[i];
                        reCurrency[i] = reCurrency[j];
                        reCurrency[j] = tmp;

                        tmp = reUnit[i];
                        reUnit[i] = reUnit[j];
                        reUnit[j] = tmp;

                        tmp = reMPQ[i];
                        reMPQ[i] = reMPQ[j];
                        reMPQ[j] = tmp;

                        tmp = reMOQ[i];
                        reMOQ[i] = reMOQ[j];
                        reMOQ[j] = tmp;

                        tmp = reLT[i];
                        reLT[i] = reLT[j];
                        reLT[j] = tmp;

                        tmp = reMonthlyBought[i];
                        reMonthlyBought[i] = reMonthlyBought[j];
                        reMonthlyBought[j] = tmp;

                        tmp = reGradeAMats[i];
                        reGradeAMats[i] = reGradeAMats[j];
                        reGradeAMats[j] = tmp;

                        tmp = reGPmaterial[i];
                        reGPmaterial[i] = reGPmaterial[j];
                        reGPmaterial[j] = tmp;

                        tmp = reAttribution[i];
                        reAttribution[i] = reAttribution[j];
                        reAttribution[j] = tmp;

                        tmp = reProvideDepartment[i];
                        reProvideDepartment[i] = reProvideDepartment[j];
                        reProvideDepartment[j] = tmp;

                        tmp = reSafeStock[i];
                        reSafeStock[i] = reSafeStock[j];
                        reSafeStock[j] = tmp;
                    };

                    var partition = function( data, left, right) {
                        var pivot = parseInt(data[right]) ;
                        var i = left -1 ;
                        for( let j = left ; j < right ; j++ ) {
                            if( (data[j] === null) || (data[j] !== null && parseInt(data[j]) < pivot ) ) {
                                i++ ;
                                swap( data, i, j) ;
                            } // if
                        } // for
                        i++;
                        swap( data, i, right) ;
                        return i ;
                    } ;

                    var quickSort = function (data, left, right) {
                        if (left < right) {
                            var pivot = partition( data, left, right) ;
                            quickSort(data, left, pivot - 1);    // 對左子串列進行快速排序
                            quickSort(data, pivot + 1, right);   // 對右子串列進行快速排序
                        } // if left < right
                    };

                    quickSort(reCheckArray, 0, reCheckArray.length - 1);

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
                } // if else

                var wholeThing = "";
                for (let x = 0; x < reIsnArray.length; ) {
                    if (x === 0) { // set the first slide active
                        wholeThing += '<div class="carousel-item active" data-bs-interval="false">';
                    } else {
                        wholeThing += '<div class="carousel-item" data-bs-interval="false">';
                    } // if else

                    for (let y = 0; y < RowsPerPage && x < reIsnArray.length; y++, x++) {
                        if (reCheckArray[x] === null) { // if 未盤點
                            $NotYetChecked++;
                            wholeThing += '\
                    <form action="" class="updateForm mb-0" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate" style="background-color: #B3E5FC;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">料號：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' + reIsnArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">品名：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reProductName[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">儲位：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reLocArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">客戶：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reClientArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">數量：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reStockArray[x] + '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;盤點結果&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid darkblue"\n\
                                               required \n\
                                               pattern="[-+]?\d*" \n\
                                               autocomplete="off">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-outline-dark btn-sm">\n\
                                            <i class="bi-clipboard-check" style="font-size: 1.5rem; color: seagreen"></i>\n\
                                            <strong class="changeBtnText">送出</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' + tablename + '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
                        } // if
                        else if (reCheckArray[x] === 0) { // if 已盤點 且盤正確
                            reCheckArray[x] = "+0";
                            wholeThing += '\
                    <form action="" class="updateForm mb-0" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate" style="background-color: #C8E6C9;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">料號：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' + reIsnArray[x] + '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">品名：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reProductName[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">儲位：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reLocArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">客戶：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reClientArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">數量：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reStockArray[x] + '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;盤正確&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid green;"\n\
                                               required \n\
                                               pattern="[-+]?\d*" \n\
                                               autocomplete="off" \n\
                                               value="' + reCheckArray[x] + '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-outline-dark btn-sm">\n\
                                            <i class="bi-clipboard-check" style="font-size: 1.5rem; color: seagreen"></i>\n\
                                            <strong class="changeBtnText">修改</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' + tablename + '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
                        } // else if
                        else if (reCheckArray[x] > 0) { // if 已盤點 且盤盈
                            reCheckArray[x] = "+" + reCheckArray[x];
                            wholeThing += '\
                    <form action="" class="updateForm mb-0" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate" style="background-color: #FFCDD2;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">料號：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' + reIsnArray[x] + '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">品名：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reProductName[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">儲位：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reLocArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">客戶：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reClientArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">數量：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reStockArray[x] + '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;盤盈&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               required \n\
                                               pattern="[-+]?\d*" \n\
                                               autocomplete="off" \n\
                                               value="' + reCheckArray[x] + '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-outline-dark btn-sm">\n\
                                            <i class="bi-clipboard-check" style="font-size: 1.5rem; color: seagreen"></i>\n\
                                            <strong class="changeBtnText">修改</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' + tablename + '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
                        } // else if
                        else { // if 已盤點 且盤虧
                            wholeThing += '\
                    <form action="" class="updateForm mb-0" method="post">\n\
                            <div class="row row-cols-2 changeOnUpdate" style="background-color: #FFCDD2;">\n\
                                <div class="col col-8 d-flex flex-wrap align-items-stretch">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="isnn" class="col-form-label">料號：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="isnn" class="isnForm form-control" \n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black;"\n\
                                               value="' + reIsnArray[x] + '" readonly>\n\
                                    </div>\n\
                                     <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="pname" class="col-form-label">品名：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="pname" class="form-control"\n\
                                               style="text-align: center; width: 15ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reProductName[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">儲位：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="locc" class="locForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reLocArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="locc" class="col-form-label">客戶：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="client" class="clientForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reClientArray[x] + '" readonly>\n\
                                    </div>\n\
                                    <div class="w-100"></div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="stockk" class="col-form-label">數量：</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input type="text" name="stockk" class="stockForm form-control"\n\
                                               style="text-align: center; width: 8ch; padding: 1px; border: 1px solid black"\n\
                                               value="' + reStockArray[x] + '" readonly>\n\
                                    </div>\n\
                                </div>\n\
                                <div class="col col-4 d-flex flex-wrap align-items-stretch justify-content-center">\n\
                                    <div class="col-auto align-self-center">\n\
                                        <label for="checkk" class="col-form-label col-auto changeLab">&nbsp;盤虧&nbsp;</label>\n\
                                    </div>\n\
                                    <div class="col-auto align-self-center">\n\
                                        <input inputmode="numeric" type="text" name="checkk" class="checkForm form-control align-self-center"\n\
                                               style="text-align: center; width: 8ch; height: 4ch; padding: 1px; border-radius: 10px; border: 3px solid red"\n\
                                               required \n\
                                               pattern="[-+]?\d*" \n\
                                               autocomplete="off" \n\
                                               value="' + reCheckArray[x] + '">\n\
                                    </div>\n\
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    <div class="col-auto align-self-center">\n\
                                        <button type="submit" class="confirmation btn btn-outline-dark btn-sm">\n\
                                            <i class="bi-clipboard-check" style="font-size: 1.5rem; color: seagreen"></i>\n\
                                            <strong class="changeBtnText">修改</strong>\n\
                                        </button>\n\
                                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->\n\
                                    </div>\n\
                                    <input type="hidden" name="tname" class="tname" value="' + tablename + '" />\n\
                                </div>\n\
                            </div>\n\
                    </form>\n\
                    <div class="w-100" style="height: 1ch;"></div>';
                        } // else if
                    } // for
                    wholeThing += '</div>';
                    totalSlides++;
                } // for

                $(".carousel-inner").html(wholeThing);
                var currentIndex = $('div.active').index() + 1;
                var Namepiece = tablename.split('_');
                if (myObj.data === null) {
                    $(".message").html('<mark><strong class="fs-2" style="color: red">No Results Found !</strong></mark>');
                } // if
                else {
                    if ($isIsn) {
                        $(".message").html(Namepiece[1] + '&nbsp;&nbsp; 此<mark><strong class="fs-4">料號</strong></mark>還有<mark><strong class="fs-2" style="color: red">' + $NotYetChecked + '</strong></mark>筆未盤');
                    } else {
                        $(".message").html(Namepiece[1] + '&nbsp;&nbsp; 此<mark><strong class="fs-4">儲位</strong></mark>還有<mark><strong class="fs-2" style="color: red">' + $NotYetChecked + '</strong></mark>筆未盤');
                    } // else
                } // else


                var tempThings = '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="prev"><i class="bi-skip-start-circle" style="font-size: 2rem;"></i></a></div>';
                tempThings += '<div class="col col-auto"><button type="button" disabled="disabled" class="btn btn-outline-success btn-sm col col-auto">' + '(' + currentIndex + '/' + totalSlides + ')</button></div>';
                tempThings += '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="next"><i class="bi-skip-end-circle" style="font-size: 2rem;"></i></a></div>';
                $('#pageCount').html(tempThings);
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
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.warn(jqXHR.responseText);
                alert("Failed : " + errorThrown + "  請複製此訊息並連絡開發人員");
            } // error
        });    // ajax
        //--------------------------------------   end               --------------------------------- //

        //--------------------------------------   multiple conditions --------------------------------- //
//        $.ajax({
//            url: "dbSearch.php",
//            type: "POST",
//            async: false,
//            data: {tablename: tablename, all: JSON.stringify(tempAll), locCount: locCount, isnCount: isnCount,
//                queryIsn: quForISN, queryLoc: quForLOC},
//            success: function (response) {
//                var myObj = JSON.parse(response);
//                reIsnArray = JSON.parse(JSON.stringify(myObj.data.isn)); // deep copy
//                reLocArray = JSON.parse(JSON.stringify(myObj.data.loc));
//                reStockArray = JSON.parse(JSON.stringify(myObj.data.stock));
//                reCheckArray = JSON.parse(JSON.stringify(myObj.data.isCheck));
//                reTimeArray = JSON.parse(JSON.stringify(myObj.data.updateTime));// reTimeArray[0].date // the first date
//                reClientArray = JSON.parse(JSON.stringify(myObj.data.client));
////                $(".message").append(reIsnArray[0]); // test
//                return false;
//            },
//            beforeSend: function () {
//                $('body').loadingModal({
//                    text: 'Loading...',
//                    animation: 'circle'
//                });
//            },
//            complete: function () {
//                $('body').loadingModal('hide');
//            },
//            error: function (jqXHR, textStatus, errorThrown) {
//                console.warn(jqXHR.responseText);
//                alert("Failed : " + errorThrown + "  請複製此訊息並連絡開發人員");
//            } // error
//        });    // ajax
//--------------------------------------   end   --------------------------------- //

//        $(".message").append("the value is : [" + reCheckArray[4] + "]<br>"); // test
//        if (reCheckArray[4] > 0) { // test
//            $(".message").append("true"); // test
//        } else { // test
//            $(".message").append("false"); // test
//        } // else

        return false;
    }); // on submit

    $(".clearBtn").on("click", 'button', function (e) {
//        e.preventDefault();
        // clear the list
        // clear all the buttons with x
//        window.location.href = "swipeToCheck.php"; // test
//        $("#texBox").focus();
    });

    $(".options").on("click", 'button', function (e) {
        e.preventDefault();
//        $(".message").html("before " + tempIsn + '<br>'); // test
        let ta = $(this).val(); // to get what isn should we remove from tempAll
        var arrResult = [];
        let indexOfIsnInTempIsn = -1;
        let indexOfLocInTempLoc = -1;
        let a = 0;
        let b = 0;
        for (let y = 0; y < tempAll.length; y++) {
            if (tempAll[y][1] === 'isn') {
                a++;
            } // if
            else if (tempAll[y][1] === 'loc') {
                b++;
            } // if else

            if (ta === tempAll[y][0] && tempAll[y][1] === 'isn') {  // suppose to set only once
                indexOfIsnInTempIsn = a;
            } // if
            else if (ta === tempAll[y][0] && tempAll[y][1] === 'loc') { // suppose to set only once
                indexOfLocInTempLoc = b;
            } // else if

            if (ta !== tempAll[y][0]) {
                arrResult.push(tempAll[y]);
            } // if
        } // for

        tempAll = arrResult.slice(0);
        if (indexOfIsnInTempIsn > 0) {
            tempIsn.splice(indexOfIsnInTempIsn - 1, 1);
        } // if

        if (indexOfLocInTempLoc > 0) {
            tempLoc.splice(indexOfLocInTempLoc - 1, 1);
        } // if

//        $(".message").append("after " + tempIsn); // test
        $(this).parent().parent().remove(); // remove the x tagged things( and its parent div)
    });

//    $(".dropdown-menu").on("click", 'button', function (e) {
//        e.preventDefault();
//        var s = document.getElementById("continueT");
//        s.value = $(this).val();
//        tablename = s.value;
//        var Namepiece = $(this).val().split('_');
//        $("#continueT").html(Namepiece[1]); // change the button to table name
//        $("#continueT").dropdown("toggle"); // close the dropdown menu
//        $("#texBox").focus();
//        return false;
//    });

    $("#main").on("click", function (e) {
        document.getElementById('jump').scrollIntoView();
        $("#texBox").focus();
    });

    $('#carouselExampleSlidesOnly').on('slid.bs.carousel', function (e) {
//        console.log("slid !"); // test
        var currentIndex = $('div.active').index() + 1;
        var tempThings = '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="prev"><i class="bi-skip-start-circle" style="font-size: 2rem;"></i></a></div>';
        tempThings += '<div class="col col-auto"><button type="button" disabled="disabled" class="btn btn-outline-success btn-sm col col-auto">' + '(' + currentIndex + '/' + totalSlides + ')</button></div>';
        tempThings += '<div class="col col-auto"><a data-bs-target="#carouselExampleSlidesOnly"  data-bs-slide="next"><i class="bi-skip-end-circle" style="font-size: 2rem;"></i></a></div>';
        $('#pageCount').html(tempThings);
    });

    $("#carouselExampleSlidesOnly").on("submit", ".updateForm", function (event) {
        event.preventDefault();
        $('.updateForm').each(function () {
            $(this).validate();
        });

        $('input.checkForm').each(function () {
            $(this).rules("add",
                    {
                        required: true,
                        pattern: /^(\+|-)?\d+$/,
                        messages: {
                            required: "Required !",
                            pattern: "Number !"
                        }
                    });
        });

        // check if form is valid
        if ($(this).validate().form()) {
//            console.log("validates"); // test
            var $isnn = $(this).find('.isnForm').val();
            var $locc = $(this).find('.locForm').val();
            var $stock = $(this).find('.stockForm').val();
            var $checkk = parseInt($(this).find('.checkForm').val());
            var $client = $(this).find('.clientForm').val();
            var $tname = $(this).find('.tname').val();
            var $pageno = "1";

//            console.log("isn : " + $(this).find('.isnForm').prop('nodeName')); // test
//            console.log("loc : " + $locc); // test
//            console.log("stock : " + $stock); // test
//            console.log("check : " + $checkk); // test

            var $w = JSON.parse(sessionStorage.getItem('isn'));
            var $x = JSON.parse(sessionStorage.getItem('loc'));
            var $y = JSON.parse(sessionStorage.getItem('stock'));
            var $z = JSON.parse(sessionStorage.getItem('check'));
            var $zz = JSON.parse(sessionStorage.getItem('client'));
            var sendd = false;
            var hasChecked = false;
            var indexC = -1;

            for (let i = 0; i < $w.length; i++) { // find the index of current input
                if ($w[i] === $isnn && $x[i] === $locc && parseInt($y[i]) === parseInt($stock) && $zz[i] === $client) {
                    indexC = i;
                    break;
                } // if
            } // for

            if (indexC >= 0 && $z[indexC] !== null) {
                hasChecked = true;
            } // if

            if (indexC >= 0 && parseInt($z[indexC]) === parseInt($checkk)) {
                sendd = confirm('Value has not changed, Are you sure?');
            } // if
            else {
                sendd = true;
            } // else

            var that = this;
            if (sendd !== true) {
                return false;
            } else {
                $.ajax({
                    url: "list_update.php",
                    type: "POST",
                    data: {isnn: $isnn, locc: $locc, checkk: $checkk, clientt: $client, pageno: $pageno, tname: $tname},
                    success: function (response) {
                        var msg = "";
                        var myObj = JSON.parse(response);
                        var $pageNo = myObj.data;
                        if (myObj.boolean === true) {
//                            window.location.reload(true); // test
//                            console.log("cc : " + $checkk); // test
                            if (hasChecked === false) {
                                $NotYetChecked--;
                            } // if

                            var Namepiece = tablename.split('_');
                            if ($isIsn) {
                                $(".message").html(Namepiece[1] + '&nbsp;&nbsp; 此<mark><strong class="fs-4">料號</strong></mark>還有<mark><strong class="fs-2" style="color: red">' + $NotYetChecked + '</strong></mark>筆未盤');
                            } else {
                                $(".message").html(Namepiece[1] + '&nbsp;&nbsp; 此<mark><strong class="fs-4">儲位</strong></mark>還有<mark><strong class="fs-2" style="color: red">' + $NotYetChecked + '</strong></mark>筆未盤');
                            } // else
                            $z[indexC] = $checkk;
                            sessionStorage.setItem('check', JSON.stringify($z));
                            let tempStr = "";
                            if ($checkk < 0) {
                                $(that).find('.checkForm').attr("value", $checkk);
                                $(that).find('.checkForm').val($checkk);
                            } else {
                                tempStr = "+" + $checkk;
                                $(that).find('.checkForm').attr("value", tempStr);
                                $(that).find('.checkForm').val(tempStr);
                            } // else

                            if (parseInt($checkk) === 0) {
                                $(that).find('.changeOnUpdate').css('background-color', '#C8E6C9');
                                $(that).find('.checkForm').css('border', '3px solid green');
                                $(that).find('.changeLab').html('&nbsp;盤正確&nbsp;');
                                $(that).find('.changeBtnText').html('修改');
                            } else if (parseInt($checkk) > 0) {
                                $(that).find('.changeOnUpdate').css('background-color', '#FFCDD2');
                                $(that).find('.checkForm').css('border', '3px solid red');
                                $(that).find('.changeLab').html('&nbsp;盤盈&nbsp;');
                                $(that).find('.changeBtnText').html('修改');
                            } else if (parseInt($checkk) < 0) {
                                $(that).find('.changeOnUpdate').css('background-color', '#FFCDD2');
                                $(that).find('.checkForm').css('border', '3px solid red');
                                $(that).find('.changeLab').html('&nbsp;盤虧&nbsp;');
                                $(that).find('.changeBtnText').html('修改');
                            } // if else if

                        } else {
                            msg = myObj.message;
                            $("#message").html(msg);
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
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.warn(jqXHR.responseText);
                        alert(errorThrown);
                    } // error
                });    // ajax
            } // else
        } else {
//            console.log("does not validate"); // test
        } // if else

    });  // on submit

    $("#carouselExampleSlidesOnly").on('click', '.confirmation', function (e) {
        $(this).parent().parent().parent().parent().submit();
//         console.log("Node Name : " + $(this).parent().parent().parent().parent().prop('nodeName') ); // test
        return false;
    });

    $("#hidingClass").on('hide.bs.collapse', function (e) {
        arrowChangeToDown();
    });

    $("#hidingClass").on('show.bs.collapse', function (e) {
        arrowChangeToUp();
    });

    $("#hidingClass").on('shown.bs.collapse', function (e) {
        $("#texBox").focus();
    });

    $("#sendTname").on('click', function (e) {
        event.preventDefault();
        tablename = $('#continueT').val();
        sessionStorage.setItem('tname', JSON.stringify(tablename));
        window.location.href = 'pageOfConsumMan.php';
        return false;
    });
}); // on document ready
