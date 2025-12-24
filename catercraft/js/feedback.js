document.getElementById("feedbackForm").addEventListener("submit", function(e){
    e.preventDefault();

    const alertBox = document.getElementById("alertMsg");
    alertBox.innerHTML = "";

    const form = this;
    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const feedback = form.feedback.value.trim();
    const rating = form.rating.value;

    if(name === "") { alertBox.innerHTML = `<div class="alert alert-danger">Name is required!</div>`; form.name.focus(); return; }
    if(email === "" || !/^\S+@\S+\.\S+$/.test(email)) { alertBox.innerHTML = `<div class="alert alert-danger">Valid email required!</div>`; form.email.focus(); return; }
    if(!rating) { alertBox.innerHTML = `<div class="alert alert-danger">Please select a rating!</div>`; return; }
    if(feedback === "") { alertBox.innerHTML = `<div class="alert alert-danger">Feedback cannot be empty!</div>`; form.feedback.focus(); return; }

    const submitBtn = form.querySelector("button[type='submit']");
    submitBtn.disabled = true;
    submitBtn.innerHTML = "Submitting... ⏳";

    const formData = new FormData(form);
    fetch("api/feedback_submit.php", {
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
        submitBtn.innerHTML = "Submit Feedback";
    });
});
