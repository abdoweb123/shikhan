<div id="token"></div>
<div id="msg"></div>
<div id="notis"></div>
<div id="err"></div>
<script src="https://www.gstatic.com/firebasejs/4.6.2/firebase.js"></script>
<script>
     MsgElem = document.getElementById("msg")
     TokenElem = document.getElementById("token")
     NotisElem = document.getElementById("notis")
     ErrElem = document.getElementById("err")

      // Initialize Firebase
      // TODO: Replace with your project's customized code snippet
      var config = {
          apiKey: "AIzaSyBtHEfcFQPiWOR7ynKGPz3aOKoE5wOocUU",
          authDomain: "yakfeekapp-a65fc.firebaseapp.com", // <PROJECT_ID>.firebaseapp.com
          databaseURL: "https://yakfeekapp-a65fc.firebaseio.com",
          storageBucket: "yakfeekapp-a65fc.appspot.com", // gs://
          messagingSenderId: "723838855261",
      };
      firebase.initializeApp(config);

      const messaging = firebase.messaging();
      messaging
          .requestPermission()
          .then(function () {
              // MsgElem.innerHTML = "Notification permission granted."
              console.log("Notification permission granted.");

              // get the token in the form of promise
              return messaging.getToken()
          })
          .then(function(token) {
              // TokenElem.innerHTML = "token is : " + token
              console.log( "token is : " + token );
          })
          .catch(function (err) {
              // ErrElem.innerHTML =  ErrElem.innerHTML + "; " + err
              console.log("Unable to get permission to notify.", err);
          });

      messaging.onMessage(function(payload) {
          console.log("Message received. ", payload);
          NotisElem.innerHTML = NotisElem.innerHTML + JSON.stringify(payload)
      });

</script>
