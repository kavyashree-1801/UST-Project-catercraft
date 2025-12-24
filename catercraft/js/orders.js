document.addEventListener('DOMContentLoaded', async () => {

    const container = document.getElementById('ordersContainer');

    if (!container) {
        console.error('ordersContainer not found');
        return;
    }

    try {
        const res = await fetch('api/orders_api.php');
        const data = await res.json();

        if (data.status !== 'success') {
            container.innerHTML = `
                <p class="text-danger text-center">
                    ${data.message || 'Unable to load orders'}
                </p>`;
            return;
        }

        const orders = data.orders || [];

        if (orders.length === 0) {
            container.innerHTML = `
                <p class="text-warning text-center fs-4">
                    You have not placed any orders yet.
                </p>`;
            return;
        }

        let html = `
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Order Date</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Items</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>`;

        orders.forEach((o, idx) => {

            /* =========================
               PAYMENT STATUS CLASS
            ========================= */
            let paymentClass = 'status-Default';
            if (o.payment_status) {
                const ps = o.payment_status.toLowerCase();
                if (ps === 'paid') paymentClass = 'status-Paid';
                else if (ps === 'cod' || ps === 'pending') paymentClass = 'status-COD';
            }

            /* =========================
               ORDER STATUS CLASS (FIXED)
            ========================= */
            let orderClass = 'status-Default';
            if (o.order_status) {
                const os = o.order_status.toLowerCase();

                if (['pending', 'placed'].includes(os)) {
                    orderClass = 'status-Pending';
                } else if (['processing', 'in progress'].includes(os)) {
                    orderClass = 'status-Processing';
                } else if (['completed', 'delivered'].includes(os)) {
                    orderClass = 'status-Completed';
                } else if (['cancelled', 'canceled'].includes(os)) {
                    orderClass = 'status-Cancelled';
                }
            }

            /* =========================
               ORDER ITEMS
            ========================= */
            const items = (o.items || [])
                .map(it => `${it.product_name} (x${it.quantity})`)
                .join('<br>');

            html += `
            <tr>
                <td>${idx + 1}</td>
                <td>${new Date(o.order_date).toLocaleString()}</td>
                <td>
                    <span class="status-badge ${paymentClass}">
                        ${o.payment_status || 'Pending'}
                    </span>
                </td>
                <td>
                    <span class="status-badge ${orderClass}">
                        ${o.order_status || 'Pending'}
                    </span>
                </td>
                <td>${items}</td>
                <td>₹${Number(o.total).toFixed(2)}</td>
            </tr>`;
        });

        html += `</tbody></table>`;
        container.innerHTML = html;

    } catch (err) {
        console.error(err);
        container.innerHTML = `
            <p class="text-danger text-center">
                Failed to load orders. Try again later.
            </p>`;
    }
});
