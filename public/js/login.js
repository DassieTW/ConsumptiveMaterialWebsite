function showpassword() {
    var x = document.getElementById("password");
    if (x.type === "password")
    {
      x.type = "text";
    }
    else
    {
      x.type = "password";
    }
}

function showpassword1() {
    var x = document.getElementById("newpassword");
    if (x.type === "password")
    {
      x.type = "text";
    }
    else
    {
      x.type = "password";
    }
}

function showpassword2() {
    var x = document.getElementById("surepassword");
    if (x.type === "password")
    {
      x.type = "text";
    }
    else
    {
      x.type = "password";
    }
};
/*
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#login_form').on('submit', function (e) {
    e.preventDefault();
      var username = $("#username").val();
      var password = $("#password").val();
    $.ajax({
       type:'POST',
       url:"member/login",
       data:{username:username, password:password},
       success:function(data){
          console.log(data.success);
       }
    });

});
*/

