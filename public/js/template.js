$(function () {
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
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    var svgArrowRight = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "svg"
    );
    var pathArrowRight = document.createElementNS(
        "http://www.w3.org/2000/svg",
        "path"
    );
    pathArrowRight.setAttribute(
        "d",
        "M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"
    );
    svgArrowRight.setAttribute("width", "16");
    svgArrowRight.setAttribute("height", "16");
    svgArrowRight.setAttribute("fill", "currentColor");
    svgArrowRight.setAttribute("class", "bi bi-arrow-right");
    svgArrowRight.setAttribute("viewBox", "0 0 16 16");
    svgArrowRight.appendChild(pathArrowRight);

    $("#passiveStockBtn").on("click", function (e) {
        e.preventDefault();
        $("body").loadingModal({
            text: "Loading...",
            animation: "circle",
        });

        window.location.href = "/bu/sluggish";
    });

    function getData() {
        // I have enabled typo in meilisearch, so the input string and result may differ a little when search by English
        return $.ajax({
            url: "/navbar_quick_search",
            type: "post",
            data: {
                inputStr: $("#instantSearchBar").val(),
            },
            dataType: "json", // let's set the expected response format
            complete: function () {
                // do nothing for now
            },
            success: function (response) {
                $("#searchResult").empty();
                // console.log(JSON.parse(response.data)); // test
                console.log(response.lang); // test
                var responseObj = JSON.parse(response.data); // get the response data
                const hits = [
                    ...new Map(
                        responseObj.hits.map((item) => [item["url"], item])
                    ).values(),
                ];
                // console.log(hits); // test
                if (hits.length > 0) {
                    $("#searchResult").show(); // show the ul list
                    for (var i = 0; i < 10 && i < hits.length; i++) {
                        if (response.lang === "en") {
                            if (
                                hits[i]._formatted.en_title.includes("<mark>")
                            ) {
                                var svg = document.createElementNS(
                                    "http://www.w3.org/2000/svg",
                                    "svg"
                                );
                                // console.log(hits[i].svg_d); //test
                                hits[i].svg_d.forEach(function (itemD, indexD) {
                                    var path = document.createElementNS(
                                        "http://www.w3.org/2000/svg",
                                        "path"
                                    );
                                    path.setAttribute("d", itemD);
                                    svg.appendChild(path);
                                });

                                svg.setAttribute("width", "16");
                                svg.setAttribute("height", "16");
                                svg.setAttribute("fill", hits[i].svg_fill);
                                svg.setAttribute("class", hits[i].svg_class);
                                svg.setAttribute("viewBox", "0 0 16 16");

                                $("#searchResult").append(
                                    '<li id="result' +
                                        i +
                                        '"><a class="dropdown-item" href="' +
                                        hits[i].url +
                                        '"><span>' +
                                        hits[i]._formatted.en_parentTitle +
                                        '</span><br class="sepLi"><span>' +
                                        hits[i]._formatted.en_title +
                                        "</span></a></li>"
                                ); // add <li> for each hit

                                $("#result" + i + " a").prepend(svg);
                                if (i + 1 < 10 && i + 1 < hits.length) {
                                    $("#searchResult").append(
                                        '<li><hr class="dropdown-divider"></li>'
                                    ); // add divider between items
                                } // for
                            } // if
                        } // if
                        else if (response.lang === "zh-TW") {
                            if (
                                hits[i]._formatted.tw_title.includes("<mark>")
                            ) {
                                var svg = document.createElementNS(
                                    "http://www.w3.org/2000/svg",
                                    "svg"
                                );
                                // console.log(hits[i].svg_d); //test
                                hits[i].svg_d.forEach(function (itemD, indexD) {
                                    var path = document.createElementNS(
                                        "http://www.w3.org/2000/svg",
                                        "path"
                                    );
                                    path.setAttribute("d", itemD);
                                    svg.appendChild(path);
                                });

                                svg.setAttribute("width", "16");
                                svg.setAttribute("height", "16");
                                svg.setAttribute("fill", hits[i].svg_fill);
                                svg.setAttribute("class", hits[i].svg_class);
                                svg.setAttribute("viewBox", "0 0 16 16");

                                $("#searchResult").append(
                                    '<li id="result' +
                                        i +
                                        '"><a class="dropdown-item" href="' +
                                        hits[i].url +
                                        '"><span>' +
                                        hits[i]._formatted.tw_parentTitle +
                                        '</span><br class="sepLi"><span>' +
                                        hits[i]._formatted.tw_title +
                                        "</span></a></li>"
                                ); // add <li> for each hit

                                $("#result" + i + " a").prepend(svg);
                                if (i + 1 < 10 && i + 1 < hits.length) {
                                    $("#searchResult").append(
                                        '<li><hr class="dropdown-divider"></li>'
                                    ); // add divider between items
                                } // for
                            } // if
                        } // else if
                        else if (response.lang === "zh-CN") {
                            if (
                                hits[i]._formatted.cn_title.includes("<mark>")
                            ) {
                                var svg = document.createElementNS(
                                    "http://www.w3.org/2000/svg",
                                    "svg"
                                );
                                // console.log(hits[i].svg_d); //test
                                hits[i].svg_d.forEach(function (itemD, indexD) {
                                    var path = document.createElementNS(
                                        "http://www.w3.org/2000/svg",
                                        "path"
                                    );
                                    path.setAttribute("d", itemD);
                                    svg.appendChild(path);
                                });

                                svg.setAttribute("width", "16");
                                svg.setAttribute("height", "16");
                                svg.setAttribute("fill", hits[i].svg_fill);
                                svg.setAttribute("class", hits[i].svg_class);
                                svg.setAttribute("viewBox", "0 0 16 16");

                                $("#searchResult").append(
                                    '<li id="result' +
                                        i +
                                        '"><a class="dropdown-item" href="' +
                                        hits[i].url +
                                        '"><span>' +
                                        hits[i]._formatted.cn_parentTitle +
                                        '</span><br class="sepLi"><span>' +
                                        hits[i]._formatted.cn_title +
                                        "</span></a></li>"
                                ); // add <li> for each hit

                                $("#result" + i + " a").prepend(svg);
                                if (i + 1 < 10 && i + 1 < hits.length) {
                                    $("#searchResult").append(
                                        '<li><hr class="dropdown-divider"></li>'
                                    ); // add divider between items
                                } // for
                            } // if
                        } // else if
                        else if (response.lang === "vi") {
                            if (
                                hits[i]._formatted.vi_title.includes("<mark>")
                            ) {
                                var svg = document.createElementNS(
                                    "http://www.w3.org/2000/svg",
                                    "svg"
                                );
                                // console.log(hits[i].svg_d); //test
                                hits[i].svg_d.forEach(function (itemD, indexD) {
                                    var path = document.createElementNS(
                                        "http://www.w3.org/2000/svg",
                                        "path"
                                    );
                                    path.setAttribute("d", itemD);
                                    svg.appendChild(path);
                                });

                                svg.setAttribute("width", "16");
                                svg.setAttribute("height", "16");
                                svg.setAttribute("fill", hits[i].svg_fill);
                                svg.setAttribute("class", hits[i].svg_class);
                                svg.setAttribute("viewBox", "0 0 16 16");

                                $("#searchResult").append(
                                    '<li id="result' +
                                        i +
                                        '"><a class="dropdown-item" href="' +
                                        hits[i].url +
                                        '"><span>' +
                                        hits[i]._formatted.vi_parentTitle +
                                        '</span><br class="sepLi"><span>' +
                                        hits[i]._formatted.vi_title +
                                        "</span></a></li>"
                                ); // add <li> for each hit

                                $("#result" + i + " a").prepend(svg);
                                if (i + 1 < 10 && i + 1 < hits.length) {
                                    $("#searchResult").append(
                                        '<li><hr class="dropdown-divider"></li>'
                                    ); // add divider between items
                                } // for
                            } // if
                        } // else if
                        else if (response.lang === "id") {
                            if (
                                hits[i]._formatted.id_title.includes("<mark>")
                            ) {
                                var svg = document.createElementNS(
                                    "http://www.w3.org/2000/svg",
                                    "svg"
                                );
                                // console.log(hits[i].svg_d); //test
                                hits[i].svg_d.forEach(function (itemD, indexD) {
                                    var path = document.createElementNS(
                                        "http://www.w3.org/2000/svg",
                                        "path"
                                    );
                                    path.setAttribute("d", itemD);
                                    svg.appendChild(path);
                                });

                                svg.setAttribute("width", "16");
                                svg.setAttribute("height", "16");
                                svg.setAttribute("fill", hits[i].svg_fill);
                                svg.setAttribute("class", hits[i].svg_class);
                                svg.setAttribute("viewBox", "0 0 16 16");

                                $("#searchResult").append(
                                    '<li id="result' +
                                        i +
                                        '"><a class="dropdown-item" href="' +
                                        hits[i].url +
                                        '"><span>' +
                                        hits[i]._formatted.id_parentTitle +
                                        '</span><br class="sepLi"><span>' +
                                        hits[i]._formatted.id_title +
                                        "</span></a></li>"
                                ); // add <li> for each hit

                                $("#result" + i + " a").prepend(svg);
                                if (i + 1 < 10 && i + 1 < hits.length) {
                                    $("#searchResult").append(
                                        '<li><hr class="dropdown-divider"></li>'
                                    ); // add divider between items
                                } // for
                            } // if
                        } // else if
                    } // for

                    if ($("#searchResult").children().length > 0) {
                        $(".sepLi").after(svgArrowRight);
                        $(".sepLi").after("&nbsp;&nbsp;&nbsp;&nbsp;");
                    } // if
                    else {
                        $("#searchResult").empty();
                        $("#searchResult").hide(); // hide the ul list
                    } // else
                } // if
                else {
                    $("#searchResult").hide(); // hide the ul list
                } // else
            },
            error: function (err) {
                console.log(err); // test
                notyf.error({
                    message: "Error",
                    duration: 5000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });
            }, // if error
        }); // end of ajax
    }

    let searchTimeout;

    $("#instantSearchBar").on("input", function (e) {
        // console.log($("#instantSearchBar").val()); // test
        if ($("#instantSearchBar").val().length > 0) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function () {
                getData();
            }, 200);
        } // if
        else {
            $("#searchResult").empty();
            $("#searchResult").hide();
        } // else
    });

    $("#instantSearchBar").on("focus", function (e) {
        if ($("#searchResult li").children().length > 0) {
            $("#searchResult").show();
        } // if
        else {
            $("#searchResult").hide();
        } // else
    });
}); // on document ready
