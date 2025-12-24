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
   LOGIN FORM
========================= */
const loginForm = document.getElementById("loginForm");

loginForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const loginType = document.getElementById("loginType");
    const loginEmail = document.getElementById("loginEmail");
    const loginPassword = document.getElementById("loginPassword");

    // Validation
    if (!loginEmail.value.trim() || !loginPassword.value.trim()) {
        alert("Please fill in all fields");
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(loginEmail.value)) {
        alert("Please enter a valid email address");
        return;
    }

    const data = {
        action: "login",
        type: loginType.value,
        email: loginEmail.value,
        password: loginPassword.value
    };

    try {
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
    } catch (error) {
        console.error(error);
        alert("Something went wrong. Please try again.");
    }
});

/* =========================
   SIGNUP FORM
========================= */
const signupForm = document.getElementById("signupForm");

signupForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const signupName = document.getElementById("signupName");
    const signupEmail = document.getElementById("signupEmail");
    const signupPhone = document.getElementById("signupPhone");
    const signupAddress = document.getElementById("signupAddress");
    const signupPassword = document.getElementById("signupPassword");
    const signupConfirm = document.getElementById("signupConfirm");

    // Validation
    if (!signupName.value.trim() || !signupEmail.value.trim() || !signupPhone.value.trim() ||
        !signupAddress.value.trim() || !signupPassword.value || !signupConfirm.value) {
        alert("Please fill in all fields");
        return;
    }

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(signupEmail.value)) {
        alert("Please enter a valid email address");
        return;
    }

    if (!/^\d{10,15}$/.test(signupPhone.value)) {
        alert("Please enter a valid phone number (10-15 digits)");
        return;
    }

    if (signupPassword.value !== signupConfirm.value) {
        alert("Passwords do not match");
        return;
    }

    if (signupPassword.value.length < 6) {
        alert("Password must be at least 6 characters long");
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

    try {
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
    } catch (error) {
        console.error(error);
        alert("Something went wrong. Please try again.");
    }
});

/* =========================
   BOOTSTRAP TOOLTIP INIT
========================= */
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
