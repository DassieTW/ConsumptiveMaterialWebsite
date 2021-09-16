
var listOfSerialNo = [];
var creatorList = [];

function appenTableContent() {
    for (let ty = 0; ty < listOfSerialNo.length; ty++) {
        let myArr = listOfSerialNo[ty].split("_");
        $('#tableHead').after($('<tr class="sheetPreview align-items-center" id="' + listOfSerialNo[ty] + '">' +
            '<td class="col col-auto align-items-center px-0 m-0"><span>' + listOfSerialNo[ty] +
            '</span></td>' +
            '<td class="col col-auto align-items-center px-0 m-0"><span>' +
            creatorList[ty] +
            '</span>' +
            '</td><td class="col col-auto align-items-center px-0 m-0"><span>' +
            myArr[1] + " " + myArr[2] +
            '</span>' +
            '</td><td class="col col-auto align-items-center px-0 m-0">' +
            '<button class="btn btn-primary col-auto checkBtn" id="' + listOfSerialNo[ty] +'" type="submit">' +
            Lang.get('checkInvLang.continue_check') +
            '</button>' +
            '</td></tr>'
        ));
    } // for

    $('.checkBtn').on('click', function(e){
        e.preventDefault();
        var strr = $(this).attr('id');
        // console.log(strr) ; // test
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/checking/set_wanted_table",
            data: { tableName: strr },
            dataType: 'json',              // let's set the expected response format
            beforeSend: function () {
                $('body').loadingModal({
                    text: 'Loading...',
                    animation: 'circle'
                });
            },
            complete: function () {
                $('body').loadingModal('hide');
            },
            success: function (response) {
                window.location.href = "/checking";
            },
            error: function (err) {
                if (err.status == 420) {  // if no result
                    console.log('set session failed.') ;
                } // else if
                else {
                    console.log(err.status); // test
                } // else
            } // error
        }); // end of ajax
    }); // on checkBtn click
} // appenTableContent

function findNameInArray(user, myObjs) {
    for (let b = 0; b < myObjs.length; b++) {
        if (myObjs[b].username === user) {
            return b;
        } // if
    } // for

    return -1;
} // findNameInArray

$(document).ready(function () {
    $('#submitBtn').on('click', function (e) {
        e.preventDefault();
        var createSignal = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/checking/create_new_table",
            data: { createSignal: createSignal },
            dataType: 'json',              // let's set the expected response format
            beforeSend: function () {
                $('body').loadingModal({
                    text: 'Loading...',
                    animation: 'circle'
                });
            },
            complete: function () {
                $('body').loadingModal('hide');
            },
            success: function (response) {
                notyf.success({
                    message: Lang.get('checkInvLang.create_table_success'),
                    duration: 3000,   //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom"
                    }
                });

                window.location.href = "/checking";
            },
            error: function (err) {
                if (err.status == 420) {  // if no result
                    notyf.error({
                        message: Lang.get('checkInvLang.create_table_fail'),
                        duration: 3000,   //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom"
                        }
                    });
                } // else if
                else {
                    console.log(err.status); // test
                } // else
            } // error
        }); // end of ajax
    }); // on submit button click

    (function () { // starting show on document ready
        var fetchSignal = true;
        // console.log($('#serialList li').length); // test
        $('#serialList li').each(function (i) {
            listOfSerialNo.push($(this).text());
        });

        // console.log(listOfSerialNo); // test

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: "/checking/get_creators",
            data: { fetchSignal: fetchSignal },
            dataType: 'json',              // let's set the expected response format
            beforeSend: function () {
                $('body').loadingModal({
                    text: 'Loading...',
                    animation: 'circle'
                });
            },
            complete: function () {
                $('body').loadingModal('hide');
            },
            success: function (response) {
                var myObjs = JSON.parse(JSON.stringify(response.data));

                for (let a = 0; a < listOfSerialNo.length; a++) {
                    let myArr = listOfSerialNo[a].split("_");
                    let index = findNameInArray(myArr[0], myObjs);
                    if (index >= 0) {
                        creatorList.push(myObjs[index].姓名);
                    } // if
                    else {
                        console.log('cannot find 姓名 !'); // test
                    } // else

                } // for

                appenTableContent();
            },
            error: function (err) {
                if (err.status == 420) {  // if no result
                    notyf.error({
                        message: Lang.get('checkInvLang.create_table_fail'),
                        duration: 3000,   //miliseconds, use 0 for infinite duration
                        ripple: true,
                        dismissible: true,
                        position: {
                            x: "right",
                            y: "bottom"
                        }
                    });
                } // else if
                else {
                    console.log(err.status); // test
                } // else
            } // error
        }); // end of ajax

    })();

}); // on document ready