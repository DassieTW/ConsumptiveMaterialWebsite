//only check one
$('.basic').on('change', function() {
    $('.basic').not(this).prop('checked', false);
});


//
$(".factory").show();
$(".client").hide();
$(".machine").hide();
$(".production").hide();
$(".line").hide();
$(".use").hide();
$(".usereason").hide();
$(".inreason").hide();
$(".position").hide();
$(".send").hide();
$(".o").hide();
$(".back").hide();
$("#download").attr("href","../download/FactoryExample.xlsx");

//
$(function () {
    $("#factory").click(function () {
        if ($(this).is(":checked")) {
            $(".factory").show();
            $(".client").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/FactoryExample.xlsx");
        } else {
            $(".factory").hide();

        }
    });

    $("#client").click(function () {
        if ($(this).is(":checked")) {
            $(".client").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/ClientExample.xlsx");
        } else {
            $(".client").hide();
        }
    });

    $("#machine").click(function () {
        if ($(this).is(":checked")) {
            $(".machine").show();
            $(".factory").hide();
            $(".client").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/MachineExample.xlsx");
        } else {
            $(".machine").hide();
        }
    });

    $("#production").click(function () {
        if ($(this).is(":checked")) {
            $(".production").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".client").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/ProductionExample.xlsx");
        } else {
            $(".production").hide();
        }
    });

    $("#line").click(function () {
        if ($(this).is(":checked")) {
            $(".line").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".client").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/LineExample.xlsx");
        } else {
            $(".line").hide();
        }
    });

    $("#use").click(function () {
        if ($(this).is(":checked")) {
            $(".use").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".client").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/UseExample.xlsx");
        } else {
            $(".use").hide();
        }
    });

    $("#usereason").click(function () {
        if ($(this).is(":checked")) {
            $(".usereason").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".client").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/UseReasonExample.xlsx");
        } else {
            $(".usereason").hide();
        }
    });

    $("#inreason").click(function () {
        if ($(this).is(":checked")) {
            $(".inreason").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".client").hide();
            $(".position").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/InReasonExample.xlsx");
        } else {
            $(".inreason").hide();
        }
    });

    $("#position").click(function () {
        if ($(this).is(":checked")) {
            $(".position").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".client").hide();
            $(".send").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/PositionExample.xlsx");
        } else {
            $(".position").hide();
        }
    });

    $("#send").click(function () {
        if ($(this).is(":checked")) {
            $(".send").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".client").hide();
            $(".o").hide();
            $(".back").hide();
            $("#download").attr("href","../download/SendExample.xlsx");
        } else {
            $(".send").hide();
        }
    });

    $("#o").click(function () {
        if ($(this).is(":checked")) {
            $(".o").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".client").hide();
            $(".back").hide();
            $(".send").hide();
            $("#download").attr("href","../download/OboundExample.xlsx");
        } else {
            $(".o").hide();
        }
    });


    $("#back").click(function () {
        if ($(this).is(":checked")) {
            $(".back").show();
            $(".factory").hide();
            $(".machine").hide();
            $(".production").hide();
            $(".line").hide();
            $(".use").hide();
            $(".usereason").hide();
            $(".inreason").hide();
            $(".position").hide();
            $(".client").hide();
            $(".o").hide();
            $(".send").hide();
            $("#download").attr("href","../download/BackReasonExample.xlsx");
        } else {
            $(".back").hide();
        }
    });

});
