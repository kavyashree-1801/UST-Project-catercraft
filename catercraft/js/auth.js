/* =========================
   EYE TOGGLER (ALL PASSWORDS)
========================= */
document.querySelectorAll(".eye").forEach(eye => {
    eye.addEventListener("click", () => {
        const input = document.getElementById(eye.dataset.toggle);
        input.type = input.type === "password" ? "text" : "password";
    });
});

/* =========================
   LOGIN
========================= */
document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const data = {
        action: "login",
        type: loginType.value,
        email: loginEmail.value,
        password: loginPassword.value
    };

    const res = await fetch("api/auth.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    });

    const result = await res.json();

    if (result.status === "success") {
        window.location.href = result.redirect;
    } else {
        alert(result.message);
    }
});

/* =========================
   SIGNUP
========================= */
document.getElementById("signupForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    if (signupPassword.value !== signupConfirm.value) {
        alert("Passwords do not match");
        return;
    }

    const data = {
        action: "signup",
        name: signupName.value,
        email: signupEmail.value,
        phone: signupPhone.value,
        address: signupAddress.value,
        password: signupPassword.value
    };

    const res = await fetch("api/auth.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    });

    const result = await res.json();

    if (result.status === "success") {
        alert("Signup successful! Please login.");
        document.querySelector('[data-bs-target="#login"]').click();
        signupForm.reset();
    } else {
        alert(result.message);
    }
});
