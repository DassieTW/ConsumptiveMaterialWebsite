var counta = $("#counta").val();
var buyarraya = [];
var needarraya = [];
var buyarrayb = [];
var needarrayb = [];
var allneed;
var allbuy;
var countb = $("#countb").val();

const reducer = (accumulator, currentValue) => accumulator + currentValue;

for (var i = 0; i < counta; i++) {
    var srmnumbera = $("#srmnumbera" + i).val();
    var buyamounta = $("#buyamounta" + i).val();
    var pricea = $("#pricea" + i).val();
    var ratea = $("#ratea" + i).val();
    var nextneeda = $("#nextneeda" + i).val();
    var needmoneya = nextneeda * pricea * ratea;
    var buymoneya = buyamounta * pricea * ratea;
    $('#needmoneya' + i).val(needmoneya);
    $('#buymoneya' + i).val(buymoneya);
    buyarraya.push(buymoneya);
    needarraya.push(needmoneya);
}

for (var i = 0; i < countb; i++) {
    var srmnumberb = $("#srmnumberb" + i).val();
    var buyamountb = $("#buyamountb" + i).val();
    var priceb = $("#priceb" + i).val();
    var rateb = $("#rateb" + i).val();
    var nextneedb = $("#nextneedb" + i).val();
    var needmoneyb = nextneedb * priceb * rateb;
    var buymoneyb = buyamountb * priceb * rateb;
    $('#needmoneyb' + i).val(needmoneyb);
    $('#buymoneyb' + i).val(buymoneyb);
    buyarrayb.push(buymoneyb);
    needarrayb.push(needmoneyb);
}

allneed = (needarraya.reduce(reducer, 0)) + (needarrayb.reduce(reducer, 0));
allbuy = (buyarraya.reduce(reducer, 0)) + (buyarrayb.reduce(reducer, 0));

for (var i = 0; i < counta; i++) {
    var pricea = $("#pricea" + i).val();
    var ratea = $("#ratea" + i).val();
    var nextneeda = $("#nextneeda" + i).val();
    var buyamounta = $("#buyamounta" + i).val();
    var buymoneya = buyamounta * pricea * ratea;
    var needmoneya = nextneeda * pricea * ratea;
    var needpera = needmoneya / allneed * 100;
    var buypera = buymoneya / allbuy * 100;
    buypera = buypera.toFixed(2);
    needpera = needpera.toFixed(2);
    $('#needpera' + i).val(needpera + '%');
    $('#buypera' + i).val(buypera + '%');
}

for (var i = 0; i < countb; i++) {
    var priceb = $("#priceb" + i).val();
    var rateb = $("#rateb" + i).val();
    var nextneedb = $("#nextneedb" + i).val();
    var buyamountb = $("#buyamountb" + i).val();
    var buymoneyb = buyamountb * priceb * rateb;
    var needmoneyb = nextneedb * priceb * rateb;
    var needperb = needmoneyb / allneed * 100;
    var buyperb = buymoneyb / allbuy * 100;
    buyperb = buyperb.toFixed(2);
    needperb = needperb.toFixed(2);
    $('#needperb' + i).val(needperb + '%');
    $('#buyperb' + i).val(buyperb + '%');
}

$(document).ready(function () {
    $("input").change(function () {
        for (var i = 0; i < counta; i++) {
            var buyamounta = $("#buyamounta" + i).val();
            var pricea = $("#pricea" + i).val();
            var ratea = $("#ratea" + i).val();
            var buymoneya = buyamounta * pricea * ratea;
            $('#buymoneya' + i).val(buymoneya);
            buyarraya.splice(i, 1, buymoneya);
        }
        for (var i = 0; i < countb; i++) {
            var buyamountb = $("#buyamountb" + i).val();
            var priceb = $("#priceb" + i).val();
            var rateb = $("#rateb" + i).val();
            var buymoneyb = buyamountb * priceb * rateb;
            $('#buymoneyb' + i).val(buymoneyb);
            buyarrayb.splice(i, 1, buymoneyb);
        }
        allbuy = (buyarrayb.reduce(reducer, 0)) + (buyarraya.reduce(reducer, 0));

        for (var i = 0; i < counta; i++) {
            var pricea = $("#pricea" + i).val();
            var ratea = $("#ratea" + i).val();
            var buyamounta = $("#buyamounta" + i).val();
            var buymoneya = buyamounta * pricea * ratea;
            var buypera = buymoneya / allbuy * 100;
            buypera = buypera.toFixed(2);
            $('#buypera' + i).val(buypera + '%');
        }
        for (var i = 0; i < countb; i++) {
            var priceb = $("#priceb" + i).val();
            var rateb = $("#rateb" + i).val();
            var buyamountb = $("#buyamountb" + i).val();
            var buymoneyb = buyamountb * priceb * rateb;
            var buyperb = buymoneyb / allbuy * 100;
            buyperb = buyperb.toFixed(2);
            $('#buyperb' + i).val(buyperb + '%');
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#buylist').on('submit', function (e) {
        e.preventDefault();

        var select = ($(document.activeElement).val());
        // clean up previous input results
        $('.is-invalid').removeClass('is-invalid');
        $(".invalid-feedback").remove();

        counta = $("#counta").val();
        countb = $("#countb").val();

        counta = counta || 0;
        countb = countb || 0;
        counta = parseInt(counta);
        countb = parseInt(countb);

        count = counta + countb;
        var srm = [];
        var client = [];
        var number = [];
        var name = [];
        var moq = [];
        var nextneed = [];
        var nowneed = [];
        var safe = [];
        var price = [];
        var money = [];
        var rate = [];
        var amount = [];
        var stock = [];
        var buyamount = [];
        var realneed = [];
        var buymoney = [];
        var buyper = [];
        var needmoney = [];
        var needper = [];

        for (let i = 0; i < counta; i++) {
            srm.push($("#srmnumbera" + i).val());
            client.push($("#clienta" + i).val());
            number.push($("#numbera" + i).val());
            name.push($("#namea" + i).val());
            moq.push($("#moqa" + i).val());
            nextneed.push($("#nextneeda" + i).val());
            nowneed.push($("#nowneeda" + i).val());
            safe.push($("#safea" + i).val());
            price.push($("#pricea" + i).val());
            money.push($("#moneya" + i).val());
            rate.push($("#ratea" + i).val());
            amount.push($("#amounta" + i).val());
            stock.push($("#stocka" + i).val());
            buyamount.push($("#buyamounta" + i).val());
            realneed.push($("#realneeda" + i).val());
            buymoney.push($("#buymoneya" + i).val());
            buyper.push($("#buypera" + i).val());
            needmoney.push($("#needmoneya" + i).val());
            needper.push($("#needpera" + i).val());
        }

        for (let i = 0; i < countb; i++) {
            srm.push($("#srmnumberb" + i).val());
            client.push($("#clientb" + i).val());
            number.push($("#numberb" + i).val());
            name.push($("#nameb" + i).val());
            moq.push($("#moqb" + i).val());
            nextneed.push($("#nextneedb" + i).val());
            nowneed.push($("#nowneedb" + i).val());
            safe.push($("#safeb" + i).val());
            price.push($("#priceb" + i).val());
            money.push($("#moneyb" + i).val());
            rate.push($("#rateb" + i).val());
            amount.push($("#amountb" + i).val());
            stock.push($("#stockb" + i).val());
            buyamount.push($("#buyamountb" + i).val());
            realneed.push($("#realneedb" + i).val());
            buymoney.push($("#buymoneyb" + i).val());
            buyper.push($("#buyperb" + i).val());
            needmoney.push($("#needmoneyb" + i).val());
            needper.push($("#needperb" + i).val());
        }

        if (select == '提交' || select == 'Submit') {
            for (let i = 0; i < (counta + countb); i++) {
                if (parseInt(buyamount[i]) > 0 && srm[i] === "") {
                    if (i < counta) {
                        $("#srmnumbera" + i).addClass("is-invalid");
                    } else {
                        let j = i - counta;
                        $("#srmnumberb" + j).addClass("is-invalid");
                    }
                    console.log(123);
                    i++;
                    var mess = Lang.get('monthlyPRpageLang.row') + ' ' + i + ' ' + Lang.get('monthlyPRpageLang.nowrite') + Lang.get('monthlyPRpageLang.srm');
                    alert(mess);
                    return false;
                } else {
                    continue;
                }
            }
        }


        if (select == '提交' || select == 'Submit') {
            $.ajax({
                type: 'POST',
                url: "buylistsubmit",
                data: {
                    srm: srm,
                    client: client,
                    number: number,
                    name: name,
                    moq: moq,
                    nextneed: nextneed,
                    nowneed: nowneed,
                    safe: safe,
                    price: price,
                    money: money,
                    rate: rate,
                    amount: amount,
                    stock: stock,
                    buyamount: buyamount,
                    realneed: realneed,
                    buymoney: buymoney,
                    buyper: buyper,
                    needmoney: needmoney,
                    needper: needper,
                    count: count,
                },
                beforeSend: function () {
                    // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
                    $('body').loadingModal({
                        text: 'Loading...',
                        animation: 'circle'
                    });
                },
                complete: function () {
                    $('body').loadingModal('hide');
                },
                success: function (data) {

                    var mess = Lang.get('monthlyPRpageLang.total') + ' ' + data.message + ' ' + Lang.get('monthlyPRpageLang.record') +
                        Lang.get('monthlyPRpageLang.PR') + Lang.get('monthlyPRpageLang.submit') + Lang.get('monthlyPRpageLang.success');
                    alert(mess);

                    window.location.href = 'buylistmake';

                },
                error: function (err) {
                    //transaction error
                    if (err.status == 420) {
                        var mess = err.responseJSON.message;
                        alert(mess);
                        return false;
                    }
                },
            });
        } else {
            var titlecount = $("#title").val();
            var title = [];
            for (let i = 0; i < titlecount; i++) {
                title.push($("#title" + i).val());
            }


            $.ajax({
                type: 'POST',
                url: "buylistdownload",
                data: {
                    title: title,
                    titlecount: titlecount,
                    data0: srm,
                    data1: client,
                    data2: number,
                    data3: name,
                    data4: moq,
                    data5: nextneed,
                    data6: nowneed,
                    data7: safe,
                    data8: price,
                    data9: money,
                    data10: rate,
                    data11: amount,
                    data12: stock,
                    data13: buyamount,
                    data14: realneed,
                    data15: buymoney,
                    data16: buyper,
                    data17: needmoney,
                    data18: needper,
                    count: count,
                    select: select,
                },
                xhrFields: {
                    responseType: 'blob', // to avoid binary data being mangled on charset conversion
                },

                beforeSend: function () {
                    // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
                    $('body').loadingModal({
                        text: 'Loading...',
                        animation: 'circle'
                    });
                },
                complete: function () {
                    $('body').loadingModal('hide');
                },

                success: function (blob, status, xhr) {

                    console.log(status); // test
                    // check for a filename

                    var filename = "";
                    var disposition = xhr.getResponseHeader('Content-Disposition');
                    if (disposition && disposition.indexOf('attachment') !== -1) {
                        var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                        var matches = filenameRegex.exec(disposition);
                        if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                    }

                    if (typeof window.navigator.msSaveBlob !== 'undefined') {
                        // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                        window.navigator.msSaveBlob(blob, filename);
                    } else {
                        var URL = window.URL || window.webkitURL;
                        var downloadUrl = URL.createObjectURL(blob);

                        if (filename) {
                            // use HTML5 a[download] attribute to specify filename
                            var a = document.createElement("a");
                            // safari doesn't support this yet
                            if (typeof a.download === 'undefined') {
                                window.location.href = downloadUrl;
                            } else {
                                a.href = downloadUrl;
                                a.download = decodeURIComponent(filename);
                                console.log(decodeURIComponent(filename));
                                document.body.appendChild(a);
                                a.click();
                            }
                        } else {
                            window.location.href = downloadUrl;
                        }

                        setTimeout(function () {
                            URL.revokeObjectURL(downloadUrl);
                        }, 100); // cleanup
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {

                    console.warn(jqXHR.responseText);
                    alert(errorThrown);
                }
            });

        }
    });
});
