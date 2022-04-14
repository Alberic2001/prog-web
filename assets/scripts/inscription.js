/* eslint-env jquery */

    //Testing password validity

    const rules = [
      // At least 8 characters
      password => password.length >= 8,
      // At least a thicc boie letter
      password => /[A-Z]/.test(password),
      // At least a smol letter
      password => /[a-z]/.test(password),
      // At least a number
      password => /[0-9]/.test(password),
      /*
      // No spaces!
      password => !/\s/.test(password),
      */
  ];
  function verifyPassword(password) {
      for (const rule of rules) {
          if (rule(password) === false) {
              return false;
          }
      }
      return true;
  }

$(document).ready(function () {

  // validate form
  $('#form_inscription').submit(function (event) {
    event.preventDefault();
    $(
      "#email , #password, #password-repeat, #nom, #prenom, #birthday"
    ).removeClass("error");
    $(".message_error").remove();

    let formValidation = true;
    const email = $("#email").val();
    const password1 = $("#password").val();
    const password2 = $("#password-repeat").val();
    const nom = $("#nom").val();
    const prenom = $("#prenom").val();
    const birthday = $("#birthday").val();

    // email
    if (email === "") {
      message_error("#email", "L'email ne doit pas être vide");
      formValidation = false;
    } else if (!ValidateEmail(email)) {
      message_error("#email", "Vous avez rentrez un email invalide !");
      formValidation = false;
    }

    // nom
    if (nom === "") {
      message_error("#nom", "Veuillez rentrer un nom");
      formValidation = false;
    } else if (nom.length > 50) {
      message_error("#nom", "Nom trop long ! > 150 caractères");
      formValidation = false;
    }

    // prenom
    if (prenom === "") {
      message_error("#prenom", "Veuillez rentrer un prenom");
      formValidation = false;
    } else if (prenom.length > 50) {
      message_error("#prenom", "Prénom trop long !");
      formValidation = false;
    }

    // Birthday
    if (!isValidDate(birthday)) {
      message_error(
        "#birthday",
        "Erreur de format de la date ! ex format : 1999/02/31"
      );
      formValidation = false;
    }

    // Validating password

    if (password1 === "") {
      message_error("#password", " Le mot de passe ne doit pas être vide !");
      formValidation = false;
    } else if (verifyPassword(password1)) {
      if (password1 !== password2) {
        message_error("#password", " Les mots de passe sont différents !");
        formValidation = false;
      }
    } else {
      message_error(
        "#password",
        " Le mot de passe doit avoir au moins 8 caractères, une majuscule et un nombre !"
      );
      formValidation = false;
    }

    //Verifying password confirmation

    if (password2 === "") {
      message_error(
        "#password-repeat",
        " Le mot de passe de confirmation ne doit pas être vide !"
      );
      formValidation = false;
    } else if (password2 !== password1) {
      message_error(
        "#password-repeat",
        " Les mots de passe ne sont pas les mêmes !"
      );
      formValidation = false;
    }

    if (!formValidation) {
      return;
    }

    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + "//" + url;
    const urlBase = url.substring(0, url.indexOf("inscription.html"));

    let form_data = $(this).serializeArray();
    form_data.push({ name: "sign_up", value: "createUser" });

    // ajax
    $.ajax({
      url: urlBase + "app/index.php",
      method: "POST",
      data: form_data,
      dataType: "json",
      success: function (response) {
        console.log(response);
        if (response.success) {
          $(
            "#email , #password, #password-repeat, #nom, #prenom, #birthday"
          ).addClass("success");
          // redirection
          window.location.replace(urlBase + "app/index.php?home=true&from=0");
        } else {
          console.log("error params");
          if (response.reason === "email") {
            message_error("#" + response.reason, response.message);
          } else {
            console.log(response.message);
            $("#" + response.reason).addClass("error");
            $("#submit").before(
              '<div class="message_error"><i class="fa fa-exclamation-triangle"></i> ' +
                response.message +
                "</div>"
            );
          }
        }
      },
      error: function () {
        console.log("error");
        $("#submit").before(
          '<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Erreur envoi de données</div>'
        );
      },
    });
  });
});

// validate email
function ValidateEmail (email) {
  if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {
    return (true)
  }
  return (false)
}

// validate that the input string is a valid date formatted as 'yyyy/mm/dd'
function isValidDate(dateString) {
  // First check for the pattern
  if (!/^\d{4}\/\d{1,2}\/\d{1,2}$/.test(dateString)) {
    return false;
  }
  // Parse the date parts to integers
  const parts = dateString.split('/');
  const day = parseInt(parts[2], 10);
  const month = parseInt(parts[1], 10);
  const year = parseInt(parts[0], 10);

  // Check the ranges of month and year
  if (year < 1000 || year > 3000 || month == 0 || month > 12) {
    return false;
  }

  let monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

  // Adjust for leap years
  if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)) {
    monthLength[1] = 29;
  }
  // Check the range of the day
  return day > 0 && day <= monthLength[month - 1];
};

function message_error(element, message) {
  $(element).addClass('error');
  $(element).after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i>' + message + '</div>');
}
