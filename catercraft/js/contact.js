document.getElementById("contactForm").addEventListener("submit", function(e){
    e.preventDefault();

    const alertBox = document.getElementById("alertMsg");
    alertBox.innerHTML = ""; // Clear previous messages

    const form = this;
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const message = form.message.value.trim();

    // ✅ Client-side validation
    if(name === "") {
        alertBox.innerHTML = `<div class="alert alert-danger">Name is required!</div>`;
        form.name.focus();
        return;
    }

    if(email === "" || !/^\S+@\S+\.\S+$/.test(email)) {
        alertBox.innerHTML = `<div class="alert alert-danger">Please enter a valid email!</div>`;
        form.email.focus();
        return;
    }

    if(message === "") {
        alertBox.innerHTML = `<div class="alert alert-danger">Message cannot be empty!</div>`;
        form.message.focus();
        return;
    }

    // Show loader
    const submitBtn = form.querySelector("button[type='submit']");
    submitBtn.disabled = true;
    submitBtn.innerHTML = "Sending... ⏳";

    // Send data via AJAX
    const formData = new FormData(form);
    fetch("api/contact_submit.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === "success") {
            alertBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            form.reset();
        } else {
            alertBox.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
    })
    .catch(err => {
        console.error(err);
        alertBox.innerHTML = `<div class="alert alert-danger">Something went wrong. Try again.</div>`;
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = "Send Message";
    });
});
