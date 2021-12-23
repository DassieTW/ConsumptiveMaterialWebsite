
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#new_people').on('submit', function (e) {

    e.preventDefault();

    // clean up previous input results
    $('.is-invalid').removeClass('is-invalid');
    $(".invalid-feedback").remove();

    var number = $("#number").val();
    var name = $("#name").val();
    var department = $("#department").val();
    $.ajax({
        type: 'POST',
        url: "new",
        data: { number: number, name: name, department: department },

        beforeSend: function () {
            // console.log('sup, loading modal triggered in CallPhpSpreadSheetToGetData !'); // test
            $('body').loadingModal({
                text: 'Loading...',
                animation: 'circle'
            });
        },
        complete: function () {
            $('body').loadingModal('hide');
            $('body').loadingModal('destroy');
        },
        success: function (data) {
            var mess = Lang.get('templateWords.newPInfo') + Lang.get('loginPageLang.success');
            alert(mess);
            window.location.reload();

        },
        error: function (err) {
            //job number not 9
            if (err.status == 420) {
                document.getElementById("message1").style.display = "block";
                document.getElementById('number').classList.add("is-invalid");
                document.getElementById('number').value = '';
                document.getElementById("message").style.display = "none";
                return false;
            }
            //job number is repeat
            else if (err.status == 421) {
                document.getElementById("message").style.display = "block";
                document.getElementById('number').classList.add("is-invalid");
                document.getElementById('number').value = '';
                document.getElementById("message1").style.display = "none";
                return false;
            }
        }
    });
});
