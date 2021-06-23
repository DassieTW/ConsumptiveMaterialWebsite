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
        } else {
            $(".send").hide();
        }
    });

});
