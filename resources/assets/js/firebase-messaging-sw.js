importScripts('https://www.gstatic.com/firebasejs/5.3.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.3.0/firebase-messaging.js');

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
messaging.setBackgroundMessageHandler(function(payload) {
  const title = "hello world";
  const options = {
    body: payload.data.status
  };
  return self.registration.showNotification(title, options);
});
