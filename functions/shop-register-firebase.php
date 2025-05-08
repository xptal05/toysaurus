<h2>Register</h2>
<form id="registerForm">
  <input type="email" id="email" placeholder="Email" required>
  <input type="password" id="password" placeholder="Password" required>
  <button type="submit">Sign Up</button>
</form>

<p>Already have an account? <a href="login.php">Login</a></p>

<script type="module">
  import {
    initializeApp
  } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js";
  import {
    getAuth,
    createUserWithEmailAndPassword
  } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";

  const firebaseConfig = {
    apiKey: "YOUR_API_KEY",
    authDomain: "YOUR_PROJECT.firebaseapp.com",
    projectId: "YOUR_PROJECT_ID",
    storageBucket: "YOUR_BUCKET.appspot.com",
    messagingSenderId: "YOUR_SENDER_ID",
    appId: "YOUR_APP_ID"
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);

  document.getElementById("registerForm").addEventListener("submit", (event) => {
    event.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    createUserWithEmailAndPassword(auth, email, password)
      .then((userCredential) => {
        alert("Registration successful!");
        window.location.href = "login.php";
      })
      .catch((error) => {
        alert(error.message);
      });
  });
</script>
