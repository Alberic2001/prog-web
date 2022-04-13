/* eslint-env jquery */
$(document).ready(function () {
  // === constants ===
  const favoriteIdKey = 'favorite';

  // === jQuery plugin ===
  jQuery.fn.outerHTML = function () {
    return jQuery('<div />').append(this.eq(0).clone()).html();
  };

  jQuery.fn.favoriteColorChange = function () {
    const List = $(this).parent().parent().attr('id');
    const actualColorClass = $(this).attr('class').split(' ')[1];
    if (actualColorClass === List) {
      $(this).removeClass(List).addClass('favoriteSelected');
    } else {
      $(this).removeClass('favoriteSelected').addClass(List);
    }
  };

  jQuery.fn.visualizePost = function () {
    const Postit = $(this).siblings('p:hidden').text().split('-');
    const Postit_id = Postit[1];
    const list = Postit[0];;
    let edition = {};
    edition.name = 'edition';

    if (list === 'postit') {
      edition.value = true;
    } else {
      edition.value = false;
    }
    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + '//' + url;

    const visualisationForm = $('<form>', { 'action': url, 'method': 'post' }).append($('<input>', { 'name': "visualisation", 'value': "postit", 'type': 'hidden' }), $('<input>', { 'name': 'postit_id', 'value': Postit_id, 'type': 'hidden' }), $('<input>', { 'name': edition.name, 'value': edition.value, 'type': 'hidden' }));
    $(this).after(visualisationForm);
    visualisationForm.submit();
  };

  // === function ===

  /*
  * save favoris and to localstorage
  * @param index = the index of the considered favoris postit
  */
  const addFavoris = function (postitRef) {
    const postit = $('li:contains(' + postitRef + ')');
    const postitPHidden = postit.find('p:hidden').outerHTML();
    const postitTitle = postit.find('h3').text();
    $('.favorite-list').append('<li class= "favorite-item">' + postitPHidden + '<h3 class="favorite-title">' + postitTitle + '</h3>' + '<i class="fa fa-trash"></i>' + '</li>');
    postit.find('.favoritePost').favoriteColorChange();
    try {
      // add favoris to local storage
      localStorage.setItem(favoriteIdKey + '-' + postitRef, postitRef);
    } catch (e) {
      console.log('err localStorage' + e)
    }
  }

  /*
  * delete favoris and from localstorage
  * @param postitRef = the index of the considered favoris product
  */
  const deleteFavoris = function (postitRef) {
    $('.favorite-list > li:contains(' + postitRef + ')').remove();
    console.log($('.favorite-list > li:contains(' + postitRef + ')'))
    // remove color
    const postit = $('li:contains(' + postitRef + ')');
    postit.find('.favoritePost').favoriteColorChange();
    try {
      // add favoris from local storage
      localStorage.removeItem(favoriteIdKey + '-' + postitRef);
    } catch (e) {
      console.log('err localStorage' + e)
    }
  }

  /*
  * get all favoris from localstorage
  */
  const getFavorisLocalStorage = function() {
    try {
      // if localstorage have element
      if (localStorage.length > 0) {
        for (let i = 0; i < localStorage.length; i++) {
          // get array with first element : index, second element : string favoris
          let keyFavoris = localStorage.key(i).toString().split('-');
          console.log(i)
          // if localStorage.key(i) is a favoris
          if (keyFavoris[0] === favoriteIdKey) {
            const Postit = $('li:contains(' + keyFavoris[1] + '-' + keyFavoris[2] + ')');
            if (Postit.length) {
              console.log(keyFavoris)
              // add favoris product
              addFavoris(keyFavoris[1] + '-' + keyFavoris[2]);
              // set style color heart of favoris to red
              /*Postit.find('.favoritePost > .fa-heart').css({ color: 'red' });*/
            } else {
              localStorage.removeItem(keyFavoris[1] + '-' + keyFavoris[2]);
            }
          }
        }
      }
    }
    catch (e) {
      console.log('err localStorage' + e)
    }
    // set style dropdown to display none
    $('.dropDown').css({ display: 'none' });
  }

  // get all favoris from local storage
  getFavorisLocalStorage();

  $('h3').click(function () {
    $(this).visualizePost();
  });

  $('#list-perso').on('click', '.suppr-postit', function() {
    const Postit = $(this).parent().siblings('p:hidden').text()
    const PostitId = Postit.split('-')[1];
    const ParentList = $(this).parent().parent();
    let form_data = [];
    const EditionName = 'deletePostit';
    const EditionValue = 'true';
    const LocationName = 'location';
    const LocationValue = 'accueil';
    form_data.push({ name: 'postit_id', value: PostitId });
    form_data.push({ name: EditionName, value: EditionValue });
    form_data.push({ name: LocationName, value: LocationValue });
    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, '/');
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
          deleteFavoris(Postit);
          ParentList.remove();
        } else {
          console.log('error');
          alert('Something went wrong after response');
        }
      },
      error: function() {
        // error
        console.log('error');
        alert('Something went wrong error try again');
      }
    });
  });

  // modifier post it
  $('.modif-postit').click(function(event) {
    const postit_id = $(this).parent().siblings('p:hidden').text().split('-')[1];
    let edition = {};
    edition.name = 'edition';
    edition.value = true;
    let url = window.location.host + window.location.pathname;
    url = url.replace(/\/+/g, "/");
    url = window.location.protocol + '//' + url;
    let editForm = $('<form>', { 'action': url, 'method': 'post' }).append($('<input>', { 'name': 'postit_id', 'value': postit_id, 'type': 'hidden' }), $('<input>', { 'name': edition.name, 'value': edition.value, 'type': 'hidden' }), $('<input>', { 'name': 'addPostitPage', 'value': 'update', 'type': 'hidden' }));
    console.log(editForm);
    $(this).after(editForm);
    editForm.submit();
  });

  $('#favorites').click(function (event) {
    const dropDownDisplay = $('.dropDown').css('display');
    if ($(event.target).is('h3')) {
      $(event.target).visualizePost();
    } else if ($(event.target).is('.favorite-item > i')) {
      const PostitRef = event.target.parentElement.firstChild.innerText;
      deleteFavoris(PostitRef);
    } else if (dropDownDisplay === 'block') {
      $('.dropDown').css({ display: 'none' });
    } else {
      $('.dropDown').css({ display: 'block' });
    }
  });

  $('.favoritePost').click(function () {
    const ClassFavoriteSelected = 'favoriteSelected';
    if ($(this).attr('class').split(' ')[1] === ClassFavoriteSelected) {
      let postitRef = $(this).siblings('p:hidden').text();
      deleteFavoris(postitRef);
    } else {
      let postitRef = $(this).siblings('p:hidden').text();
      addFavoris(postitRef);
    }
  });
});
