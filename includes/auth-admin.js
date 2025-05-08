import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";

const auth = getAuth();
onAuthStateChanged(auth, (user) => {
  if (user) {
    user.getIdTokenResult().then((idTokenResult) => {
      if (!idTokenResult.claims.admin) {
        alert("Access denied!");
        window.location.href = "/public/login.php";
      }
    });
  } else {
    window.location.href = "/public/login.php";
  }
});

document.getElementById("logout").addEventListener("click", () => {
  signOut(auth).then(() => {
    localStorage.removeItem("admin");
    window.location.href = "/public/login.php";
  });
});
