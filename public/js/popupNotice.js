
document.addEventListener("DOMContentLoaded", function (event) {
    var notyf = new Notyf();
    notyf.success({
        message: "Success. Dismiss to retry.",
        duration: 0,
        ripple: true,
        dismissible: true,
        position: {
            x: "right",
            y: "bottom"
        }
    });
});