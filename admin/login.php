<?php include '../includes/header.php'; ?>

<h2>Admin Login</h2>
<form id="adminLoginForm">
    <input type="email" id="adminEmail" placeholder="Admin Email" required>
    <input type="password" id="adminPassword" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

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

  document.getElementById("adminLoginForm").addEventListener("submit", (event) => {
    event.preventDefault();
    const email = document.getElementById("adminEmail").value;
    const password = document.getElementById("adminPassword").value;

    signInWithEmailAndPassword(auth, email, password)
      .then((userCredential) => {
        const user = userCredential.user;
        user.getIdTokenResult().then((idTokenResult) => {
          if (idTokenResult.claims.admin) {
            localStorage.setItem("admin", JSON.stringify(user));
            window.location.href = "index.php";
          } else {
            alert("Not an admin!");
          }
        });
      })
      .catch((error) => {
        alert(error.message);
      });
  });
</script>

<?php include '../includes/footer.php'; ?>
