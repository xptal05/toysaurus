
<h2>Login</h2>
<form id="loginForm">
    <input type="email" id="email" placeholder="Email" required>
    <input type="password" id="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="./shop-register">Register</a></p>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js";
  import { getAuth, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";

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

  document.getElementById("loginForm").addEventListener("submit", (event) => {
    event.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    signInWithEmailAndPassword(auth, email, password)
      .then((userCredential) => {
        alert("Login successful!");
        localStorage.setItem("user", JSON.stringify(userCredential.user));
        window.location.href = "shop/index.php";
      })
      .catch((error) => {
        alert(error.message);
      });
  });
</script>


<!--
Optional: Google Login
import { GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";

const provider = new GoogleAuthProvider();

document.getElementById("googleLogin").addEventListener("click", () => {
  signInWithPopup(auth, provider)
    .then((result) => {
      alert("Google Login Successful!");
      localStorage.setItem("user", JSON.stringify(result.user));
      window.location.href = "shop/index.php";
    })
    .catch((error) => {
      alert(error.message);
    });
});
<button id="googleLogin">Login with Google</button>

-->
