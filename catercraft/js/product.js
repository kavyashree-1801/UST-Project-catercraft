document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.getElementById('cartBtn');
    const cartDropdown = document.getElementById('cartDropdown');
    const cartBadge = document.getElementById('cartBadge');
    const cartContainer = document.getElementById('cartItemsContainer');

    // Toggle cart dropdown
    cartBtn.addEventListener('click', () => {
        cartDropdown.classList.toggle('active');
        // Fetch updated cart items
        fetch('api/cart_items_api.php')
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success'){
                    cartBadge.textContent = data.cart_count;
                    if(data.items.length){
                        cartContainer.innerHTML = '';
                        data.items.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'cart-item';
                            div.innerHTML = `
                                <img src="${item.image}">
                                <div class="flex-grow-1">
                                    <div>${item.name}</div>
                                    <div>Qty: ${item.qty} | ₹${item.subtotal.toFixed(2)}</div>
                                </div>`;
                            cartContainer.appendChild(div);
                        });
                        cartContainer.innerHTML += `<div class="cart-total">Total: ₹${data.total.toFixed(2)}</div>`;
                        cartContainer.innerHTML += `<a href="checkout.php" class="checkout-btn">Checkout</a>`;
                    } else {
                        cartContainer.innerHTML = '<div style="padding:10px;">Your cart is empty.</div>';
                    }
                }
            });
    });

    // Add to Cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', async e => {
            e.preventDefault();
            const productId = btn.dataset.id;
            const formData = new FormData();
            formData.append('product_id', productId);

            try {
                const res = await fetch('api/cart_api.php', { method:'POST', body:formData });
                const data = await res.json();
                if(data.status === 'success'){
                    cartBadge.textContent = data.cart_count;
                    alert('Added to cart!');
                } else {
                    alert(data.message);
                }
            } catch(err){
                alert('Something went wrong. Try again.');
                console.error(err);
            }
        });
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', e => {
        if(!cartBtn.contains(e.target) && !cartDropdown.contains(e.target)){
            cartDropdown.classList.remove('active');
        }
    });
});