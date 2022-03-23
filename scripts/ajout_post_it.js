$(document).ready(function () {
  // validate form
  $('#form_post').submit(function (event) {
    event.preventDefault();
    let titre = $('#titre').val();
    let contenu = $('#contenu').val();

    $('#titre , #contenu').removeClass('error');
    $('.message_error').remove();

    if (titre.length === 0) {
      $('#titre').addClass('error');
      $('#titre').after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Le titre ne doit pas être vide</div>');
    }
    if (titre.length > 150) {
      $('#titre').addClass('error');
      $('#titre').after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Titre trop long ! plus de 150 caractères</div>');
    }
    if (contenu.length === 0 || !contenu.trim()) {
      $('#contenu').addClass('error');
      $('#contenu').after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Le contenu du Postit ne doit pas être vide</div>');
    }
  });

    // liste des utilisateurs partagé 

    // get date 

    // get propriétaire

    // mode edition
});