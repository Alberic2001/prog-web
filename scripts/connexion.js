/* eslint-env jquery */
$(document).ready(function () {
  // login form
  $('#form-connexion').submit(function (event) {
    event.preventDefault();
    $('#email , #password').removeClass('error');
    $('.message_error').remove();

    let formValidation = true;

    // check if email or password empty
    if ($('#password').val() === '') {
      message_error('#password', 'Le mot de passe ne doit pas être vide !');
      formValidation = false;
    } else if ($('#password').val().length < 6) {
      message_error('#password', 'La longueur du mot de passe est insufisante !');
      formValidation = false;
    }

    if ($('#email').val() === '') {
      message_error('#email', "L'email ne doit pas être vide");
      formValidation = false;
    }

    if (!formValidation) {
      return;
    }

    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + '//' + url;
    let urlBase = url.substring(0, url.indexOf('connexion.html'));
    console.log(urlBase);

    let form_data = $(this).serializeArray();
    form_data.push({ name: "login", value: "loginUser" });

    $.ajax({
      url: urlBase + 'database/index.php',
      method: 'POST',
      data: form_data,
      dataType: 'json',
      crossDomain: true,
      success: function (response) {
        if (response.success) {
          $('#email , #password').addClass('success');
          console.log('success');
          window.location.replace(urlBase + 'database/index.php?home=true');
        } else {
          console.log('error');
          if (response.reason !== 'email' && response.reason !== 'password') {
            $('#login').before('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> ' + response.message + '</div>');
          } else {
            console.log('email or password')
            message_error('#' + response.reason, response.message);
          }
        }
      },
      error: function () {
        // error
        $('#login').before('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Erreur envoi de données</div>');
        console.log('error');
      }
    });
  });
});

function message_error (element, message) {
  $(element).addClass('error');
  $(element).after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i>' + message + '</div>');
}
