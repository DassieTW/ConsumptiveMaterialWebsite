/**
 * sends a request to the specified url from a form. this will change the window location.
 * @param {string} path the path to send the post request to
 * @param {object} params the paramiters to add to the url
 * @param {string} [method=post] the method to use on the form
 */
function post(path, params, method = 'post') { // submit in a traditoinal way( redirect to the php)
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

$(document).ready(function () {
    $("#newT").on("click", function (e) {
        e.preventDefault();
        var forreal = 1;
        $.ajax({
            url: "createNewTable.php",
            type: "POST",
            data: {forreal: forreal},
            success: function (response) {
                var myObj = JSON.parse(response);
                if (myObj.boolean === true) {
                    window.location.href = "Search.php";
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
        });    // ajax
        return false;
    });

    $("#beforeChecking").on("click", function (e) {
        e.preventDefault();
        var forreal = 0;
        $.ajax({
            url: "createNewTable.php",
            type: "POST",
            data: {forreal: forreal},
            success: function (response) {
                var myObj = JSON.parse(response);
                var wholeMenu = "";
                if (myObj.boolean === true) {
//                    console.log(response); // test
                    if (myObj.data.length > 0) {
                        document.getElementById('continueT').disabled = false; // 變更欄位為啟用 

                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = yyyy + '-' + mm + '-' + dd;
                        var dateInName = myObj.data[0].split('_');
//                        console.log( today + " vs " + dateInName[1] ) ; // test

                        var tablename = myObj.data[0];
                        var arrayNamepiece = myObj.data[0].split('_');
                        $("#dateChoose").html(arrayNamepiece[1]); // change the button to table name
                        var s = document.getElementById("dateChoose"); // // change the button value to table name
                        s.value = myObj.data[0];
                        for (let i = 0; i < myObj.data.length; i++) {
                            arrayNamepiece = myObj.data[i].split('_');
                            wholeMenu += '<li><button class="dropdown-item" type="button" value="' + myObj.data[i] + '">' + arrayNamepiece[1] + '</button></li>';
                        } // for

                        $(".dropdown-menu").html(wholeMenu);

                        if (today === dateInName[1]) {
                            document.getElementById('newT').disabled = true; // 變更欄位為禁用
                            $(".statueMessage").html('已有<strong>今日內</strong>的盤點表，請續盤。');
                        } // if
                        else {
                            $(".statueMessage").html('已有期間內的盤點表，請選擇續盤或建立新的盤點表。');
                        } // else
                    } // if
                    else {
                        document.getElementById('continueT').disabled = true; // 變更欄位為禁用
                        $(".statueMessage").html('無期間內的盤點表，請建立新的盤點表。');
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
        });    // ajax
        return false;
    });

    $(".dropdown-menu").on("click", 'button', function (e) {
        e.preventDefault();
        var s = document.getElementById("dateChoose");
        s.value = $(this).val();
        tablename = s.value;
        var Namepiece = $(this).val().split('_');
        $("#dateChoose").html(Namepiece[1]); // change the button to table name
        $("#dateChoose").dropdown("toggle"); // close the dropdown menu
        return false;
    });

    $("#continueT").on("click", function (e) {
        e.preventDefault();
        sessionStorage.clear();
        var calling = true;
        var s = document.getElementById("dateChoose");
        var tablename = s.value;
        sessionStorage.setItem('fromHomePage', JSON.stringify(calling));
        sessionStorage.setItem('tname', JSON.stringify(tablename));
        window.location.href = 'Search.php';
        return false;
    });

    var menu_btn = document.querySelector("#menu-btn");
    var sidebar = document.querySelector("#sidebar");
    var container = document.querySelector(".my-container");
    menu_btn.addEventListener("click", () => {
        sidebar.classList.toggle("active-nav");
        container.classList.toggle("active-cont");
    });
    
//    var clickOnload = document.getElementById("menu-btn"); // click once to hide initiallly
//    clickOnload.click();
}); // on document ready 
