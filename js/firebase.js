// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
var firebaseConfig = {
    apiKey: "AIzaSyBj8PL0Us3kBFWCRnJV_ypPeCOxhB8r24A",
    authDomain: "asdp-eoffice.firebaseapp.com",
    databaseURL: "https://asdp-eoffice.firebaseio.com",
    projectId: "asdp-eoffice",
    storageBucket: "asdp-eoffice.appspot.com",
    messagingSenderId: "110115942194",
    appId: "1:110115942194:web:46144393eb8f6d1e07312b",
    measurementId: "G-GDSW49XS1X"
  };
// Initialize Firebase
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

navigator.serviceWorker.register(base_url+'js/firebase-sw.js')
.then(registration => {
    messaging.useServiceWorker(registration)
})
.catch(err => console.log('Service Worker Error', err))

messaging.onMessage(function(payLoad)
{
    console.log("Message Received");
    console.log(payLoad);
    notificationTitle = payLoad.notification.title;
    notificationOptions = {
        body: payLoad.notification.body,
        icon: payLoad.notification.icon,
    };
    // var notification = new Notification(notificationTitle, notificationOptions);

    alert(notificationTitle+': '+payLoad.notification.body);
});

