$(document).ready(function () {
    //
    //                       _oo0oo_
    //                      o8888888o
    //                      88" . "88
    //                      (| -_- |)
    //                      0\  =  /0
    //                    ___/`---'\___
    //                  .' \\|     |// '.
    //                 / \\|||  :  |||// \
    //                / _||||| -:- |||||- \
    //               |   | \\\  -  /// |   |
    //               | \_|  ''\---/''  |_/ |
    //               \  .-\__  '-'  ___/-. /
    //             ___'. .'  /--.--\  `. .'___
    //          ."" '<  `.___\_<|>_/___.' >' "".
    //         | | :  `- \`.;`\ _ /`;.`/ - ` : | |
    //         \  \ `_.   \_ __\ /__ _/   .-` /  /
    //     =====`-.____`.___ \_____/___.-`___.-'=====
    //                       `=---='
    //
    //
    //     ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    //
    //               佛祖保佑         永無BUG
    //
    //
    //

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#passiveStockBtn").on("click", function (e) {
        e.preventDefault();
        $('body').loadingModal({
            text: 'Loading...',
            animation: 'circle'
        });

        window.location.href = "/bu/sluggish";
    });

    $("#instantSearchBar").on("input", function (e) {
        e.preventDefault();
        if ($("#instantSearchBar").val().length > 0) {
            $.ajax({
                url: "/navbar_quick_search",
                type: 'post',
                data: {
                    inputStr: $("#instantSearchBar").val()
                },
                dataType: 'json', // let's set the expected response format
                complete: function () {
                    // do nothing for now
                },
                success: function (response) {
                    $("#searchResult").empty();
                    console.log(JSON.parse(response.data)); // test
                    // console.log(response.lang); // test
                    var responseObj = JSON.parse(response.data); // get the response data
                    //console.log(responseObj.hits); // test
                    if (responseObj.hits.length > 0) {
                        $("#searchResult").show(); // show the ul list
                        for (var i = 0; i < 10 && i < responseObj.hits.length; i++) {
                            $("#searchResult").append('<li><a class="dropdown-item" href="#">123</a></li>'); // add <li> for each hit
                            if( (i+1) < 10 && (i+1) < responseObj.hits.length) {
                                $("#searchResult").append('<li><hr class="dropdown-divider"></li>'); // add divider between items
                            } // for 
                        } // for
                    } // if
                    else {
                        $("#searchResult").hide(); // hide the ul list
                    } // else
                },
                error: function (err) {
                    console.log(err.responseJSON.message); // test
                    notyf.error({
                        message: "Error",
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
            $("#searchResult").empty();
            $("#searchResult").hide();
        } // else
    });
}); // on document ready
