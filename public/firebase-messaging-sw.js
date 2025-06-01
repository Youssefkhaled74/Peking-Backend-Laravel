importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');
let config = {
        apiKey: "AIzaSyBzn0sQhbFYdw7LHpcFL8ciH2NB6zFy33Q",
        authDomain: "peking-ea5ac.firebaseapp.com",
        projectId: "peking-ea5ac",
        storageBucket: "peking-ea5ac.firebasestorage.app",
        messagingSenderId: "281636976730",
        appId: "1:281636976730:web:251215cbf08c805ec90063",
        measurementId: "G-M7SJ91J644",
 };
firebase.initializeApp(config);
const messaging = firebase.messaging();
messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/default/firebase-logo.png'
    };
    self.registration.showNotification(notificationTitle, notificationOptions);
});
