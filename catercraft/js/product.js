document.addEventListener('DOMContentLoaded', () => {

    /* =========================
       ELEMENTS
    ========================= */
    const cartBtn = document.getElementById('cartBtn');
    const cartDropdown = document.getElementById('cartDropdown');
    const cartBadge = document.getElementById('cartBadge');
    const cartContainer = document.getElementById('cartItemsContainer');

    /* =========================
       SAFETY CHECK
    ========================= */
    if (!cartBtn || !cartDropdown || !cartBadge || !cartContainer) {
        console.error('Cart elements missing in HTML');
        return;
    }

    /* =========================
       TOGGLE CART DROPDOWN
    ========================= */
    cartBtn.addEventListener('click', e => {
        e.stopPropagation();
        cartDropdown.classList.toggle('active');

        fetch('api/cart_items_api.php')
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') return;

                cartBadge.textContent = data.cart_count;

                if (data.items.length === 0) {
                    cartContainer.innerHTML =
                        '<div class="p-2 text-center">Your cart is empty.</div>';
                    return;
                }

                cartContainer.innerHTML = '';

                data.items.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'cart-item';
                    div.innerHTML = `
                        <img src="${item.image}" alt="${item.name}">
                        <div class="flex-grow-1">
                            <div>${item.name}</div>
                            <div>Qty: ${item.qty} | ₹${item.subtotal.toFixed(2)}</div>
                        </div>
                    `;
                    cartContainer.appendChild(div);
                });

                cartContainer.innerHTML += `
                    <div class="cart-total">Total: ₹${data.total.toFixed(2)}</div>
                    <a href="checkout.php" class="checkout-btn">Checkout</a>
                `;
            })
            .catch(err => console.error(err));
    });

    /* =========================
       ADD TO CART
    ========================= */
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {

        btn.addEventListener('click', async e => {
            e.preventDefault();
            e.stopPropagation();

            const productId = btn.getAttribute('data-id');

            if (!productId) {
                alert('Product ID not found!');
                return;
            }

            const formData = new FormData();
            formData.append('product_id', productId);

            try {
                const res = await fetch('api/cart_api.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.status === 'success') {
                    cartBadge.textContent = data.cart_count;
                    alert('Added to cart!');
                } else {
                    alert(data.message || 'Failed to add item');
                }

            } catch (err) {
                console.error(err);
                alert('Something went wrong. Try again.');
            }
        });

    });

    /* =========================
       CLOSE CART ON OUTSIDE CLICK
    ========================= */
    document.addEventListener('click', () => {
        cartDropdown.classList.remove('active');
    });

});
