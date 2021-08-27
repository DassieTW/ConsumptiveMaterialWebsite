$(document).ready(function () {
    $('#logoutbtn').on('click', function (e) {
        localStorage.clear();
        sessionStorage.clear();
    }); // on logoutbtn click
}); // on document ready