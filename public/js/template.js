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
        $.ajax({
            url: "/navbar_quick_search",
            type: 'post',
            data: {
                inputStr: $("#instantSearchBar").val()
            },
            dataType: 'json', // let's set the expected response format
            beforeSend: function () {
                
            },
            complete: function () {
                
            },
            success: function (response) {

            },
            error: function (err) {
                console.log(err.responseJSON.message); // test
                notyf.error({
                    message: "Some err",
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
    });

    $("#quickSearchForm").on("submit", function (e) {
        e.preventDefault();
    });
}); // on document ready
