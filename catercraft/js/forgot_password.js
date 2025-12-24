document.getElementById("forgotForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const messageDiv = document.getElementById("message");
    messageDiv.innerHTML = ""; // clear previous

    if (!email) {
        messageDiv.innerHTML = `<div class="alert alert-danger">Please enter your email.</div>`;
        return;
    }

    try {
        const response = await fetch("/catercraft/api/forgot_password_submit.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}`
        });

        const text = await response.text();
        console.log("Server response:", text);

        const result = JSON.parse(text);

        if (response.ok && result.status === "success") {
            // Show link in frontend
            messageDiv.innerHTML = `
                <div class="alert alert-success">
                    ${result.message}
                </div>

                <div class="client-link-container mt-3">
                    <p><strong>Your reset link:</strong></p>
                    <a href="${result.link}" class="btn btn-link" target="_blank" id="reset-link">
                        ${result.link}
                    </a>
                </div>

                <p class="text-muted small">
                    You can click the link above or copy‑paste it into your browser.
                </p>
            `;
        } else {
            messageDiv.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
        }
    } catch (err) {
        console.error("Fetch error:", err);
        messageDiv.innerHTML = `<div class="alert alert-danger">Something went wrong. Please try again later.</div>`;
    }
});
