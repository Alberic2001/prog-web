/* eslint-env jquery */
$(document).ready(function () {
// edit post it
  if ($('#edit-post-it')) {
    $('#edit-post').click(function () {
      const PostitId = $(this).siblings('p:hidden').text()
      let url = window.location.host + window.location.pathname;
      url = url.replace(/\/+/g, "/");
      url = window.location.protocol + '//' + url;
      let editForm = $('<form>', {'action': url, 'method': 'post'}).append($('<input>', {'name': 'postit_id', 'value': PostitId, 'type': 'hidden'}), $('<input>', {'name': 'addPostitPage', 'value': 'update', 'type': 'hidden'}));
      $(this).after(editForm);
      editForm.submit();
    });

    $('#delete-post').click(function () {
      const PostitId = $(this).siblings('p:hidden').text()
      const EditionName = 'deletePostit';
      const EditionValue = 'true';
      const LocationName = 'location';
      const LocationValue = 'visualisationPostit';
      let url = window.location.host + window.location.pathname;
      url = url.replace(/\/+/g, "/");
      url = window.location.protocol + '//' + url;
      let deleteForm = $('<form>', {'action': url, 'method': 'post'}).append($('<input>', {'name': 'postit_id', 'value': PostitId, 'type': 'hidden'}), $('<input>', {'name': EditionName, 'value': EditionValue, 'type': 'hidden'}), $('<input>', {'name': LocationName, 'value': LocationValue, 'type': 'hidden'}));
      $(this).after(deleteForm);
      deleteForm.submit();
    });
  }
});
