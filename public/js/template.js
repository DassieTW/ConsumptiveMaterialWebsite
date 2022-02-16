$(document).ready(function () {
    $("#passiveStockBtn").on("click", function (e) {
        e.preventDefault();
        $('body').loadingModal({
            text: 'Loading...',
            animation: 'circle'
        });

        window.location.href = "/bu/sluggish";
    });
}); // on document ready