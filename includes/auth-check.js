import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";

const auth = getAuth();
onAuthStateChanged(auth, (user) => {
  if (!user) {
    window.location.href = "/public/login.php";
  }
});

document.getElementById("logout").addEventListener("click", () => {
  signOut(auth).then(() => {
    localStorage.removeItem("user");
    window.location.href = "/public/login.php";
  });
});
