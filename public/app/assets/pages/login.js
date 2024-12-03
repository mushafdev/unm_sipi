
$(".show-pass").on('click', function(e) {
    e.preventDefault();
    var x =document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
      $('.show-pass i').attr('class', "bi bi-eye" );
    } else {
      $('.show-pass i').attr('class', "bi bi-eye-slash" );
      x.type = "password";
    }
});

var urlReload=$('#login').data('reload-captcha');
$('.reload-cpt').click(function () {
    $.ajax({
        type: 'GET',
        url: urlReload,
        beforeSend: function() {
          $('.reload-cpt').attr('disabled',true);
          $('.reload-cpt i').attr('class', 'spinner-border spinner-border-sm');
        },
        success: function (data) {
            $(".img-captcha").attr('src', data.captcha);
            $(".img-captcha").on('load', function() {
              $('.reload-cpt').attr('disabled',false);
              $('.reload-cpt i').attr('class', 'bi bi-arrow-repeat');
          });
        },
    });
});

function showPass() {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  }

 