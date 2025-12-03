// assets/js/google-login.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup }
  from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

import { firebaseConfig } from "./firebase-config.js";

// Init Firebase
const app  = initializeApp(firebaseConfig);
const auth = getAuth(app);

const googleBtn = document.getElementById("googleLoginBtn");

if (googleBtn) {
  googleBtn.addEventListener("click", () => {
    const provider = new GoogleAuthProvider();

    signInWithPopup(auth, provider)
      .then(result => result.user.getIdToken())
      .then(token => {
        return fetch("google_auth.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id_token=" + encodeURIComponent(token)
        });
      })
      .then(res => res.text())
      .then(data => {
        console.log("google_auth.php response:", data);
        if (data.trim() === "OK") {
          window.location.href = "index.php";
        } else {
          alert("Authentication failed: " + data);
        }
      })
      .catch(err => {
        console.error(err);
        alert("Google sign-in error. Check console for details.");
      });
  });
} else {
  console.warn("googleLoginBtn not found on this page.");
}
