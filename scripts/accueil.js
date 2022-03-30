/* eslint-env jquery */
$(document).ready(function () {
    // visualisation post it
    $('h3').click(function (event) {
        let url = window.location.host + window.location.pathname;
        const postit_id = $(this).siblings('p:hidden').text()
        const parent = $(this).closest('ul').attr('id');
        let edition = new Object();
         edition.name = 'edition';

        if(parent === 'list-perso') {
            edition.value = true;
        } else {
            edition.value = false;
        }
        console.log(edition)
        console.log(parent)
        url = url.replace(/\/+/g, "/");
        url = window.location.protocol + '//' + url;
        let urlBase = url.substring(0, url.indexOf('accueil.php'));
        console.log(event);
        console.log(urlBase);
          let form_data = Array();
          form_data.push({ name : "visualisation", value : "postit" });
          form_data.push(edition);
          form_data.push({ name : "postit_id", value : postit_id });
          console.log(form_data)
          //window.location.replace(urlBase+'visualisation_post_it.php');
          const visualisationForm = $('<form>', {'action': urlBase+'visualisation_post_it.php', 'method': 'post'}).append($('<input>', {'name': 'postit_id', 'value': postit_id, 'type': 'hidden'}), $('<input>', {'name': edition.name, 'value': edition.value, 'type': 'hidden'}));
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
        const urlBase = url.substring(0, url.indexOf('accueil.php'));
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
    
    // supprimer post it
    /*$('.suppr-postit').click(function () {
        const postit_id = $(this).parent().siblings('p:hidden').text();
        console.log(postit_id);
        let edition = new Object();
        edition.name = 'deletePostit';
        edition.value = 'true';
        let form_data = Array();
        let location = new Object();
        location.name = 'location';
        location.value = 'accueil';
        form_data.push({ name: "postit_id", value: postit_id });
        form_data.push(location);
        form_data.push(edition);
        console.log(form_data)
        let url = window.location.host + window.location.pathname;
        url = url.replace(/\/+/g, "/");
        url = window.location.protocol + '//' + url;
        const urlBase = url.substring(0, url.indexOf('accueil.php'));

        $.ajax({
            url: urlBase + 'database/index.php',
            method: 'POST',
            data: form_data,
            dataType: 'json',
            success: function(response) {
            console.log(response)

            if(response.success){
              console.log('success');
              console.log($(this).closest('li'));
              console.log('remove');
              $(this).parent('.btn-group').remove();
              $(this).next().remove();
              $(this).remove();
              $(this).closest('li').remove();
              $(this).parent().parent().remove();
            } else {
              // error
              console.log('error');
              alert('Something went wrong');
            }
          },
            error: function () {
              // error
              console.log('error');
              alert('Something went wrong error ');
            }
          });

    });*/

    $('#post-it-perso').on('click', '.suppr-postit', function(){
        const postit_id = $(this).parent().siblings('p:hidden').text();
        console.log(postit_id);
        let edition = new Object();
        edition.name = 'deletePostit';
        edition.value = 'true';
        let form_data = Array();
        let location = new Object();
        location.name = 'location';
        location.value = 'accueil';
        form_data.push({ name: "postit_id", value: postit_id });
        form_data.push(location);
        form_data.push(edition);
        console.log(form_data)
        let url = window.location.host + window.location.pathname;
        url = url.replace(/\/+/g, "/");
        url = window.location.protocol + '//' + url;
        const urlBase = url.substring(0, url.indexOf('accueil.php'));
    
        $.ajax({
            url: urlBase + 'database/index.php',
            method: 'POST',
            data: form_data,
            dataType: 'json',
            success: function(response) {
            console.log(response)
    
            if(response.success){
              console.log('success');
              console.log($(this).closest('li'));
              console.log('on remove');
              /*$(this).parent('.btn-group').remove();
              $(this).next().remove();
              $(this).remove();*/
              $(this).prev().remove(); 		
              $(this).remove(); 	
              /*$(this).closest('ul').remove();*/
              /*$(this).parent().remove();*/
            } else {
              // error
              console.log('error');
              alert('Something went wrong');
            }
          },
            error: function () {
              // error
              console.log('error');
              alert('Something went wrong error ');
            }
          });
        });
});
