// Toggle visibility for both password fields
document.querySelectorAll(".toggle-password").forEach(toggle => {
    toggle.addEventListener("click", function () {
        const targetId = this.getAttribute("data-target");
        const input = document.getElementById(targetId);
        const icon = this.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye-fill");
            icon.classList.add("bi-eye-slash-fill");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye-slash-fill");
            icon.classList.add("bi-eye-fill");
        }
    });
});

document.getElementById("resetForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const messageDiv = document.getElementById("resetMessage");
    messageDiv.innerHTML = "";  // Clear old messages

    const token = document.getElementById("token").value;
    const newPassword = document.getElementById("new_password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    if (newPassword !== confirmPassword) {
        messageDiv.innerHTML = `<div class="alert alert-danger">Passwords do not match.</div>`;
        return;
    }

    try {
        const response = await fetch("api/update_password.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `token=${encodeURIComponent(token)}&new_password=${encodeURIComponent(newPassword)}&confirm_password=${encodeURIComponent(confirmPassword)}`
        });

        const text = await response.text();
        console.log("Server response:", text);
        const result = JSON.parse(text);

        if (response.ok && result.status === "success") {
            messageDiv.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
            setTimeout(() => {
                window.location.href = "/catercraft/login.php";
            }, 2000);
        } else {
            messageDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
        }
    } catch (err) {
        console.error("Fetch error:", err);
        messageDiv.innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again later.</div>`;
    }
});
