/* eslint-env jquery */
$(document).ready(function () {
    // edit post it
    if($('#edit-post-it')) {
        $('#edit-post').click(function () {
        const postit_id = $(this).siblings('p:hidden').text()
        let edition = new Object();
        edition.name = 'edition';
        edition.value = true;
        let url = window.location.host + window.location.pathname;
        url = url.replace(/\/+/g, "/");
        url = window.location.protocol + '//' + url;
        const urlBase = url.substring(0, url.indexOf('visualisation_post_it.php'));
        console.log(urlBase);
          let form_data = Array();
          form_data.push({ name : "edit", value : "postit" });
          form_data.push(edition);
          form_data.push({ name : "postit_id", value : postit_id });
          console.log(form_data)
          let editForm = $('<form>', {'action': urlBase+'ajout_post_it.php', 'method': 'post'}).append($('<input>', {'name': 'postit_id', 'value': postit_id, 'type': 'hidden'}), $('<input>', {'name': edition.name, 'value': edition.value, 'type': 'hidden'}));
          $(this).after(editForm);
          editForm.submit();
        });

        $('#delete-post').click(function () {
            const postit_id = $(this).siblings('p:hidden').text()
            let edition = new Object();
            edition.name = 'deletePostit';
            edition.value = 'true';

            let location = new Object();
            location.name = 'location';
            location.value = 'visualisationPostit';
            let url = window.location.host + window.location.pathname;
            url = url.replace(/\/+/g, "/");
            url = window.location.protocol + '//' + url;
            const urlBase = url.substring(0, url.indexOf('visualisation_post_it.php'));
            let deleteForm = $('<form>', {'action': urlBase+'database/index.php', 'method': 'post'}).append($('<input>', {'name': 'postit_id', 'value': postit_id, 'type': 'hidden'}), $('<input>', {'name': edition.name, 'value': edition.value, 'type': 'hidden'}), $('<input>', {'name': location.name, 'value': location.value, 'type': 'hidden'}));
            $(this).after(deleteForm);
            deleteForm.submit();
        });
    }
    /*
    // modifier post it
    $('.modif-postit').click(function (event) {

    });

    // supprimer post it
    $('.suppr-postit').click(function (event) {
    });*/
});