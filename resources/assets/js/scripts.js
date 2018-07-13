$(function() {

  $('.carousel').carousel({
    interval: 2000
  });

  $('.song').on('click', function() {
    $('.song').removeClass('active');
    $(this).addClass('active');
    var url = $(this).attr("value");
    $('#player').attr('src', url);
  });

  $('.like').on('click', function() {
    var title = $(this).parents('.d-flex').children('.title').text();
    var update;
    // Send Request
    $.ajax({
      type: 'POST',
      url: '/playlist/ajax/like',
      cache: false,
      async: false,
      data: {'action': 'like', 'title': title},
      beforeSend: function(xhr) {
        return xhr.setRequestHeader('X-CSRF-Token', $('meta[name=csrf-token]').attr("content"));
      },
      complete: function(response){
        update = response.responseText
      }
    });
    $(this).parents('.d-flex').children('.likes').children('.badge').text(update);
  });

});
