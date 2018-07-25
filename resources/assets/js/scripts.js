$(function() {

  // CHECK FOR SERVICE WORKER SUPPORT
  if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
            // Registration was successful
            console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, function(err) {
            // registration failed :(
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}

  // INITIALIZE FIREBASE
  var config = {
    apiKey: "AIzaSyD4GZHiIkIw53RbMCzlQLCAQhn5GzOvhWU",
    authDomain: "favorites-of-the-month.firebaseapp.com",
    databaseURL: "https://favorites-of-the-month.firebaseio.com",
    projectId: "favorites-of-the-month",
    storageBucket: "favorites-of-the-month.appspot.com",
    messagingSenderId: "621278055684"
  };
  firebase.initializeApp(config);

  const messaging = firebase.messaging();

  messaging.requestPermission().then(function() {
    console.log('have permission');
    return messaging.getToken();
  }).then(function(token) {
    // $.ajax({
    //   type: 'POST',
    //   url: '/home/notify',
    //   cache: false,
    //   async: false,
    //   data: {'action': 'notify_auth', 'token': token},
    //   beforeSend: function(xhr) {
    //     return xhr.setRequestHeader('X-CSRF-Token', $('meta[name=csrf-token]').attr("content"));
    //   },
    //   complete: function(response){
    //     update = response.responseText
    //   }
    // });
    console.log(token);
  }).catch(function(err) {
    console.log('error occured');
  });

  messaging.onMessage(function(payload) {
    console.log('onMessage', payload);
  });

  window.setTimeout(function() {
    var key = 'AAAAkKcOzQQ:APA91bHwbPGGvW3Y0PQBprdevn7zWRYUFadnArrcwHPZ9-SmhXFexD4AHdiwyTxLHX6kHaXd38rRGQzOXCGR1ScwdokD24IJdHMNlwqLQl-hOfXxR7gf6osHP2II9g9n62B3jbBCo--C0Y6dYHrRspqtgD7aVr1A7Q';
    var to = 'cAGjp00tjQw:APA91bGKPWs5fBZY9MVvgVmS_8bg-ZRI32JoJ4d-HrZ0SvpXxnN5sXuEITwd8RP3QmAtmde4K3AoNTOn-GXcophhY_6S-l3pFS-34zgHGcanlelZzeQxgtWyuw6xVjJUg1LbX6lJZi6y';
    var notification = {
      'title': 'July - New Song',
      'body': 'Zack Beveridge added: Never Gonna Give You Up - Rick Astley',
      'icon': '/assets/icon_512.png',
      'click_action': 'http://localhost/playlist'
    };

    fetch('https://fcm.googleapis.com/fcm/send', {
      'method': 'POST',
      'headers': {
        'Authorization': 'key=' + key,
        'Content-Type': 'application/json'
      },
      'body': JSON.stringify({
        'notification': notification,
        'to': to
      })
    }).then(function(response) {
      console.log(response);
    }).catch(function(error) {
      console.error(error);
    });
  }, 2000);


  $('.carousel').carousel({
    interval: 3000
  });

  $('.song').on('click', function() {
    $('.song').removeClass('active');
    $(this).addClass('active');
    var url = $(this).attr("value");
    $('#player').attr('src', url);
  });

  $('.like').on('click', function() {
    var title = $(this).parents('.d-flex').children('.col-10').children('.row').children('.title').text();
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

$(window).on('load', function() {
  var url = window.location.pathname;
  $("a[href='"+url+"']").addClass('active');
});
