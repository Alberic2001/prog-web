/* eslint-env jquery */
$(document).ready(function () {
  // visualisation post it
  $('h3').click(function (event) {
    const postit_id = $(this).siblings('p:hidden').text()
    const parent = $(this).closest('ul').attr('id');
    let edition = new Object();
    edition.name = 'edition';

    if (parent === 'list-perso') {
      edition.value = true;
    } else {
      edition.value = false;
    }
    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + '//' + url;

    const visualisationForm = $('<form>', {'action': url, 'method': 'post'}).append($('<input>',{'name': "visualisation", 'value': "postit", 'type': 'hidden'}),$('<input>', {'name': 'postit_id', 'value': postit_id, 'type': 'hidden'}), $('<input>', {'name': edition.name, 'value': edition.value, 'type': 'hidden'}));
    $(this).after(visualisationForm);
    visualisationForm.submit();
  });

  // modifier post it
  $('.modif-postit').click(function (event) {
    const postit_id = $(this).parent().siblings('p:hidden').text();
    let edition = new Object();
    edition.name = 'edition';
    edition.value = true;
    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + '//' + url;

    let editForm = $('<form>', {'action': url, 'method': 'post'}).append($('<input>', {'name': 'postit_id', 'value': postit_id, 'type': 'hidden'}), $('<input>', {'name': edition.name, 'value': edition.value, 'type': 'hidden'}), $('<input>', {'name': 'addPostitPage', 'value': 'update', 'type': 'hidden'}));
    $(this).after(editForm);
    editForm.submit();
  });

  $('#list-perso').on('click', '.suppr-postit', function() {
    const PostitId = $(this).parent().siblings('p:hidden').text();
    console.log('$(this).parent()')

    const ParentList = $(this).parent().parent();
    let form_data = [];
    const EditionName = 'deletePostit';
    const EditionValue = 'true';
    const LocationName = 'location';
    const LocationValue = 'accueil';
    form_data.push({ name: "postit_id", value: PostitId });
    form_data.push({ name: EditionName, value: EditionValue });
    form_data.push({ name: LocationName, value: LocationValue });
    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + '//' + url;

    $.ajax({
      url: url,
      method: 'POST',
      data: form_data,
      dataType: 'json',
      success: function(response) {
        console.log(response)
        if (response.success) {
          console.log('success');
          ParentList.remove();
        } else {
          console.log('error');
          alert('Something went wrong after response');
        }
      },
      error: function () {
        // error
        console.log('error');
        alert('Something went wrong error try again');
      }
    });
  });
});
