$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

$(document).ready(function () {
    function quickSearch() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = $("#numbersearch").val();
        //var isISN = $("#toggle-state").is(":checked");
        console.log(input); // test
        // filter = input.value;
        // Loop through all table rows, and hide those who don't match the search query
        $(".isnRows").each(function (i, obj) {
            txtValue = $(this).find("input[id^='username']").val();
            // console.log("now checking text : " + txtValue); // test
            if (txtValue.indexOf(input) > -1) {
                obj.style.display = "";
            } else {
                obj.style.display = "none";
            } // if else
        });
    } // quickSearch function

    $("#numbersearch").on("input", function (e) {
        e.preventDefault();
        quickSearch();
    });

    var all = $("#count").val();
    if ($("#siteListPicker").length) {
        var options = ["0", "1", "2", "3", "4"];
    } else {
        var options = ["1", "2", "3", "4"];
    } // if else

    for (let i = 0; i < all; i++) {
        var selectElement = document.getElementById("priority" + i);
        var temppriority = $("#priority" + i).val();
        for (let j = 0; j < options.length; j++) {
            if (temppriority !== options[j]) {
                var option = document.createElement("option");
                option.value = options[j];
                option.text = options[j];
                selectElement.appendChild(option);
            } // for
        } // for
    } // for

    $("select").on("change", function () {
        var id = this.id.slice(8);
        var username = $("#username" + id).val();
        var priority = this.value;
        $.ajax({
            type: "POST",
            url: "usernamechangeordel",
            data: {
                username: username,
                priority: priority,
            },
            beforeSend: function () {
                // // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
                // $("body").loadingModal({
                //   text: "Loading...",
                //   animation: "circle",
                // });
            },
            complete: function () {
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
                // location.reload();
            },
            success: function (data) {
                notyf.open({
                    type: "success",
                    message:
                        Lang.get("loginPageLang.user") +
                        Lang.get("loginPageLang.change") +
                        Lang.get("loginPageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });
                setTimeout(function () {
                    location.reload();
                }, 1000);
            },
            error: function (err) {
                console.log(err);

                var mess = err.responseJSON.message;
                alert(mess);
            },
        });
    });

    $(".dbInfo").on("click", function () {
        // console.log($(this).attr("id")); // test
        const dblist = $(this).attr("value").split("_");
        dblist.forEach((db) => {
            $("[id='" + db + "']").prop("checked", true);
        });

        $(".modal-title").text(
            "Available Databases for " +
                $(this).attr("id").split("_")[0] +
                "(" +
                $(this).attr("id").split("_")[1] +
                ")"
        );

        $(".modal-title").attr('id', $(this).attr("id").split("_")[0]);
    });

    $("#ListConfirm").on("click", function () {
        var dblist_str = "";
        $(".dbCheckbox").each(function () {
            // console.log($(this).val()); // test
            if ($(this).is(":checked")) {
                if (dblist_str == "") {
                    dblist_str = $(this).val();
                } else {
                    dblist_str = dblist_str + "_" + $(this).val();
                } // if else
            } // if
        });

        userName = $(".modal-title").attr("id");
        console.log(dblist_str); // test

        $.ajax({
            type: "POST",
            url: "/member/update_available_dblist",
            data: {
                username: userName,
                list: dblist_str,
            },
            beforeSend: function () {
                // // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
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
                notyf.open({
                    type: "success",
                    message:
                        Lang.get("loginPageLang.change") + " " +
                        Lang.get("loginPageLang.success"),
                    duration: 3000, //miliseconds, use 0 for infinite duration
                    ripple: true,
                    dismissible: true,
                    position: {
                        x: "right",
                        y: "bottom",
                    },
                });
                setTimeout(function () {
                    location.reload();
                }, 1000);
            },
            error: function (err) {
                console.log(err);

                var mess = err.responseJSON.message;
                alert(mess);
            },
        }); // ajax
    });
});
