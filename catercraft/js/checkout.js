document.addEventListener('DOMContentLoaded', () => {
    const checkoutForm = document.getElementById('checkoutForm');
    const payButton = document.getElementById('payButton');

    checkoutForm.addEventListener('submit', (e) => {
        e.preventDefault(); // prevent form submission

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;

        if (paymentMethod === 'online') {
            // Razorpay online payment
            const options = {
                "key": "rzp_test_RpWNCqNDK2nkKU", // replace with your key
                "amount": TOTAL_AMOUNT * 100, // in paise
                "currency": "INR",
                "name": "CaterCraft",
                "description": "Food Order Payment",
                "handler": function(response) {
                    // Pass payment ID to your success page
                    window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id;
                },
                "theme": { "color": "#0a884f" }
            };
            const rzp = new Razorpay(options);
            rzp.open();
        } else {
            // COD payment
            window.location.href = "payment_success.php?payment_method=cod";
        }
    });
});
