$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

var imgclicked = false;
$(document).ready(function () {
    $("#fordatabase").on("click", function (event) {
        event.preventDefault();
        if (imgclicked === false) {
            $("#site").append(
                $("<option/>", {
                    value: "Consumables management",
                    text: "TEST database",
                })
            );
        }
        imgclicked = true;
    });

    $("#registerform").on("submit", function (e) {
        e.preventDefault();

        // clean up previous input results
        $(".is-invalid").removeClass("is-invalid");
        $(".invalid-feedback").hide();

        var site = $("#site").val();
        var job_id = $("#job_id").val();
        var p_name = $("#p_name").val();
        var email = $("#email").val();
        var dep = $("#dep").val();

        var profilePic = $(
            "input[name=flexRadioDefault]:checked",
            "#registerform"
        ).val();

        if (site == "" || site === null) {
            document.getElementById("site_error").style.display = "block";
            document.getElementById("site").classList.add("is-invalid");
            document.getElementById("site").focus();
            return false;
        } // if

        if (profilePic === undefined) {
            profilePic = 0; // set to default if not select any
            // document.getElementById("picerror").style.display = "block";
            // document.getElementById("radio1").focus();
            // $("input[type=radio]").each(function () {
            //   $(this).addClass("is-invalid");
            // });
            // return false;
        } // if

        console.log(profilePic + site); //test

        $.ajax({
            type: "POST",
            url: "/member/register",
            data: {
                site: site,
                profilePic: profilePic,
                job_id: job_id,
                p_name: p_name,
                email: email,
                dep: dep,
            },
            beforeSend: function () {
                $("body").loadingModal({
                    text: "Loading...",
                    animation: "circle",
                });
            },
            complete: function () {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
            },

            success: function (data) {
                console.log(data);
                window.location.href = "/home";
            },
            error: function (err) {
                console.log(err);
            },
        });
    });
});
