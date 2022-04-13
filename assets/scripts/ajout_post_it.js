/* eslint-env jquery */
$(document).ready(function () {
  let shared_init = [];
  let create = true;
  let oldInputUserSearch = ''; ///

  // si post it à modifier
  if ($('h2').text().trim() === 'MODIFIER POST IT') {
    shared_init = $('.checkbox_user:checkbox:checked').siblings('input:hidden').clone();
    create = false;
  }

  $('#users-search').on('input', function (event) {
    let userInput = this.value.toLowerCase();
    $('ul.users-list li').each(function () {
      let email = $(this).find('.checkbox_user').val();
      if (email.indexOf(userInput) !== -1) {
        $(this).fadeIn();//Show
      } else {
        $(this).fadeOut();//Hide
      }
    });
  });

  const popupAction = function (removedClass, addedClass, popupMessage) {
    $('.popup-text').text(popupMessage);
    $('.popup-overlay').removeClass(removedClass).addClass(addedClass);
  }

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
        form_data[key].value = $("input[value='" + form_data[key].value + "']").siblings('input:hidden').attr('id').split('-')[1];
      }
    });

    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + '//' + url;

    if (create) {
      form_data.push({ name: "addPostit", value: "create" });
    } else {
      form_data.push({ name: "addPostit", value: "update" });
      Object.keys(shared_init).forEach((key) => {
        if (shared_init[key].id != null) {
          form_data.push({ name: "shared_init[]", value: shared_init[key].id.split('-')[1] });
        }
      });
      form_data.push({ name: "postit_id", value: $('#postit_id').val() });
    }

    $.ajax({
      url: url,
      method: 'POST',
      data: form_data,
      dataType: 'json',
      crossDomain: true,
      success: function (response) {
        if (response.success) {
          let popupMessage;
          if (create) {
            popupMessage = 'le postit a été crée';
            $('#btn_reset').click();
            popupAction('display', 'active', popupMessage);
          } else {
            popupMessage = 'le postit a été modifié';
            popupAction('display', 'active', popupMessage);
            $('.popup-close').on('click', function () {
              window.location.replace(url + '?home=true');
            });
          }
        } else {
          if (create) {
            popupAction('display', 'active', "le postit n'a pas été crée");
          } else {
            popupAction('display', 'active', "le postit n'a pas été modifié");
          }
          $('#btn_reset').click();
        }
      },
      error: function () {
        // error
        console.log('error requete');
        popupAction('display', 'active', "une erreur s'est produite veuillez recommencer");
      }
    });
  });

  $('.popup-close').click(function() {
    console.log('popup close')
    popupAction('active', 'display', '')
  })
});