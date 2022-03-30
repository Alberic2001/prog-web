/* eslint-env jquery */
$(document).ready(function () {
  // validate form
  $('#form_post').submit(function (event) {
    event.preventDefault();
    const title = $('#title').val();
    const content = $('#content').val();

    $('#title , #content').removeClass('error');
    $('.message_error').remove();

    let formValid = true;

    if (title.length === 0) {
      $('#title').addClass('error');
      $('#title').after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Le titre ne doit pas être vide</div>');
      formValid = false;
    }
    if (title.length > 150) {
      $('#title').addClass('error');
      $('#title').after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Titre trop long ! plus de 150 caractères</div>');
      formValid = false;
    }
    if (content.length === 0 || !content.trim()) {
      $('#content').addClass('error');
      $('#content').after('<div class="message_error"><i class="fa fa-exclamation-triangle"></i> Le contenu du Postit ne doit pas être vide</div>');
      formValid = false;
    }

    if (!formValid) {
      return;
    }

    let form_data = $(this).serializeArray();

    // bind hidden id and email
    Object.keys(form_data).forEach((key) => {
      if (form_data[key].name === 'user_id[]') {
        form_data[key].value = $("input[value='" + form_data[key].value + "']").siblings('input:hidden').attr('id');
      }
    });

    console.log(form_data)

    let url = window.location.protocol + "//" + window.location.host + "/" + window.location.pathname;
    console.log(url)
    let urlIndex = url.substring(0, url.indexOf('ajout_post_it.php'));
    urlIndex += 'database/index.php';
    form_data.push({ name: "ajoutPostit", value: "true" });
    form_data.push({ name: "edition", value: "create" });
    $.ajax({
      url: urlIndex,
      method: 'POST',
      data: form_data,
      dataType: 'json',
      crossDomain: true,
      success: function (response) {
        console.log(response)
        if (response.success) {
          console.log('success');
          console.log(response);
          $('#btn_reset').click();
        } else {
          console.log('error');
        }
      },
      error: function () {
        // error
        console.log('error requete');
      }
    });

  });
});