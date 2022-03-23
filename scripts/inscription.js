$(document).ready(function () {
  let requestAjax; 

  // validate form
  $('#form_inscription').submit(function (event) {
    event.preventDefault();
    $('#email , #password, #password-repeat, #nom, #prenom, #birthday').removeClass('error');
    $('.message_error').remove();

    let formValidation = true;

    const email = $('#email').val();
    const password1 = $('#password').val();
    const password2 = $('#password-repeat').val();
    const nom = $('#nom').val();
    const prenom = $('#prenom').val();
    const birthday = $('#birthday').val();

    function ValidateEmail(mail) {
      if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return (true)
      }
      return (false)
    }

    // email
    if(email === '') {
      $('#email').addClass('error');
      $('#email').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> L'email ne doit pas être vide</div>");
      formValidation = false;
    } else if(!ValidateEmail(email)) {
      $('#email').addClass('error');
      $('#email').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Vous avez rentrez un email invalide !</div>");
      formValidation = false;
    }

    // nom
    if (nom === '') {
      $('#nom').addClass('error');
      $('#nom').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Veuillez rentrer un nom </div>");
      formValidation = false;
    } else if(nom.length > 50) {
      $('#nom').addClass('error');
      $('#nom').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Nom trop long !</div>");
      formValidation = false;
    }

    // prenom
    if (prenom === '') {
      $('#prenom').addClass('error');
      $('#prenom').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Veuillez rentrer un prenom </div>");
      formValidation = false;
    } else if(prenom.length > 50) {
      $('#prenom').addClass('error');
      $('#prenom').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Prenom trop long !</div>");
      formValidation = false;
    }

    //Birthday
    // Validates that the input string is a valid date formatted as 'mm/dd/yyyy'
    function isValidDate(dateString) {
    // First check for the pattern
      if (!/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(dateString))
        return false;

      // Parse the date parts to integers
      let parts = dateString.split('/');
      let day = parseInt(parts[2], 10);
      let month = parseInt(parts[1], 10);
      let year = parseInt(parts[0], 10);

      // return alert(`Day :${day} Month :${month} year:${year}`);

      // Check the ranges of month and year
      if (year < 1000 || year > 3000 || month == 0 || month > 12)
        return false;

      let monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

      // Adjust for leap years
      if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;

      // Check the range of the day
      return day > 0 && day <= monthLength[month - 1];
    };

    // birthday
    if (!isValidDate(birthday)) {
      $('#birthday').addClass('error');
      $('#birthday').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Erreur de format de la date!</div>");
      formValidation = false;
    }

    if (password1 === '') {
      $('#password').addClass('error');
      $('#password').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Le mot de passe ne doit pas être vide !</div>");
      formValidation = false;
    } else if (password1.length < 6) {
      $('#password').addClass('error');
      $('#password').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Mot de passe trop faible !</div>");
      formValidation = false;
    }
    if (password2 === '') {
      $('#password-repeat').addClass('error');
      $('#password-repeat').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Le mot de passe de confirmation ne doit pas être vide !</div>");
      formValidation = false;
    }
    // Check confirm password

    if (password1 !== password2) {
      $('#password, #password-repeat').addClass('error');
      $('#password, #password-repeat').after("<div class='message_error'><i class='fa fa-exclamation-triangle'></i> Les mots de passe ne sont pas les mêmes !</div>");
      formValidation = false;
    }

    if(formValidation) {
      let form_data = $(this).serialize();
      form_data += "&inscription=createUser";
      console.log(JSON.stringify(form_data))
      let requestAjax;
      requestAjax = $.ajax({
        url: './database/index.php',
        method: 'POST',
        data: JSON.stringify(form_data),
        contentType: 'application/json; charset=utf-8',
        success: function(response) {
          console.log('success response :');
          console.log(response)
        },
        error: function () {
          // error
          console.log('error');
        }
      });
    }
  });
});
