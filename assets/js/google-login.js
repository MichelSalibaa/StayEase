// assets/js/google-login.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup }
  from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

import { firebaseConfig } from "./firebase-config.js";

// Init Firebase
const app  = initializeApp(firebaseConfig);
const auth = getAuth(app);

const googleBtn = document.getElementById("googleLoginBtn");

// 🔵 ABSOLUTE PATH to your backend script
// Your page is at:   http://localhost/stayease/login.php
// Backend file is at: http://localhost/stayease/backend/google_auth.php
const GOOGLE_AUTH_URL = "/stayease/backend/google_auth.php";

if (googleBtn) {
  googleBtn.addEventListener("click", () => {
    const provider = new GoogleAuthProvider();

    signInWithPopup(auth, provider)
      .then(result => result.user.getIdToken())
      .then(token => {
        return fetch(GOOGLE_AUTH_URL, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id_token=" + encodeURIComponent(token)
        });
      })
      .then(res => {
        if (!res.ok) {
          // If it's a 404 or other HTTP error, make it obvious
          return res.text().then(text => {
            throw new Error("HTTP " + res.status + ": " + text);
          });
        }
        return res.text();
      })
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
        alert("Google sign-in error: " + err.message);
      });
  });
} else {
  console.warn("googleLoginBtn not found on this page.");
}
