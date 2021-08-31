var notyf = new Notyf({
    types: [
        {
            type: 'info',
            background: '#4287f5',
            icon: {
                className: 'bi bi-info-circle-fill', // I'm using Bootstrap Icons
                tagName: 'i', // <i></i> html tag
                color: 'white' // icon color
            }
        },
        {
            type: 'warning',
            background: '#f5a142',
            icon: {
                className: 'bi bi-exclamation-diamond-fill', // I'm using Bootstrap Icons
                tagName: 'i', // <i></i> html tag
                color: 'white' // icon color
            }
        },
    ]
});

document.addEventListener("DOMContentLoaded", function (event) {
    // usage example
    
    // notyf.open({
    //     type: 'info',
    //     duration: 5000,
    //     message: 'Send us <b>an email</b> to get support',
    //     ripple: true,
    //     dismissible: true,
    // });

    // notyf.success({
    //     message: "Success. Dismiss to retry.",
    //     duration: 3000,   //miliseconds, use 0 for infinite duration
    //     ripple: true,
    //     dismissible: true,
    //     position: {
    //         x: "right",
    //         y: "bottom"
    //     }
    // });
});