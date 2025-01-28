<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firebase Phone Authentication</title>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        input {
            margin: 10px;
            padding: 10px;
            width: 200px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Phone Authentication</h1>
    <div>
        <input type="text" id="phone" placeholder="Enter phone number">
        <button onclick="sendOTP()">Send OTP</button>
    </div>
    <div id="recaptcha-container"></div>
    <div>
        <input type="text" id="otp" placeholder="Enter OTP">
        <button onclick="verifyOTP()">Verify OTP</button>
    </div>
    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyAPPLEZmf3ZWfIwT9xnMy2mFGQVC_jtR8I",
            authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
            projectId: "eessystem-c4f73",
            storageBucket: "YOUR_PROJECT_ID.appspot.com",
            messagingSenderId: "YOUR_MESSAGING_SENDER_ID",
            appId: "YOUR_APP_ID"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        let verificationId;

        function sendOTP() {
            const phoneNumber = document.getElementById('phone').value;
            const appVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');

            firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                .then((confirmationResult) => {
                    verificationId = confirmationResult.verificationId;
                    alert('OTP sent!');
                })
                .catch((error) => {
                    console.error('Error during signInWithPhoneNumber', error);
                    alert('Error sending OTP: ' + error.message);
                });
        }

        function verifyOTP() {
            const verificationCode = document.getElementById('otp').value;

            firebase.auth().signInWithCredential(firebase.auth.PhoneAuthProvider.credential(verificationId, verificationCode))
                .then((result) => {
                    alert('OTP verified! User is signed in.');
                })
                .catch((error) => {
                    console.error('Error verifying OTP', error);
                    alert('Error verifying OTP: ' + error.message);
                });
        }
    </script>
</body>
</html>