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
        $('#password').addClass('error');
        $('#password').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Le mot de passe ne doit pas être vide !</div>");
        formValidation = false;
      } else if ($('#password').val().length < 6) {
        $('#password').addClass('error');
        $('#password').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> La longueur du mot de passe est insufisante !</div>");
        formValidation = false;
      }

      if($('#email').val() === '') {
        $('#email').addClass('error');
        $('#email').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> L'email ne doit pas être vide</div>");
        formValidation = false;
      }

      if(!formValidation) {
        return;
      }

      let url = window.location.host + window.location.pathname;
      url = url.replace(/\/+/g, "/");
      url = window.location.protocol + '//' + url;
      let urlBase = url.substring(0, url.indexOf('connexion.html'));
      console.log(urlBase);
    
      
        let form_data = $(this).serializeArray();
        form_data.push({ name : "login", value : "loginUser" });
        $.ajax({
          url: urlBase + 'database/index.php',
          method: 'POST',
          data: form_data,
          dataType: 'json',
          crossDomain: true,
          success: function(response) {
            if(response.success){
              $('#email , #password').addClass('success');
            console.log('success');
            window.location.replace(urlBase+'accueil.php');
            } else {
              console.log('error');
              const reason = response.reason;
              const message = response.message;
              if (reason !== 'email' && reason !== 'password') {
                $('#login').before('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> ' + message + '</div>');
              } else {
                console.log('email or password')
                console.log(message);
                $('#' + reason).addClass('error');
                $('#' + reason).after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> ' + message + '</div>');
              }
            }
          },
          error: function () {
            // error
            $('#login').before('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Something went wrong</div>');
            console.log('error');
          }
        });
    });
  });
