document.addEventListener('DOMContentLoaded', () => {

    const messageBox = document.getElementById('message');

    function showMessage(msg, type='success') {
        messageBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
    }

    function togglePassword(id) {
        const field = document.getElementById(id);
        field.type = field.type === "password" ? "text" : "password";
    }

    window.togglePassword = togglePassword; // make global for onclick

    // Profile update
    document.getElementById('profileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetch('api/profile_api.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            showMessage(data.message, data.status==='success'?'success':'danger');
        } catch(err) {
            showMessage('Something went wrong', 'danger');
            console.error(err);
        }
    });

    // Password change
    document.getElementById('passwordForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        try {
            const res = await fetch('api/profile_api.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            showMessage(data.message, data.status==='success'?'success':'danger');
            e.target.reset();
        } catch(err) {
            showMessage('Something went wrong', 'danger');
            console.error(err);
        }
    });

});
