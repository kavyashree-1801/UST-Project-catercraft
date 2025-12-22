document.addEventListener('DOMContentLoaded', async () => {
    const container = document.getElementById('ordersContainer');

    try {
        const res = await fetch('api/orders_api.php');
        const data = await res.json();

        if (data.status !== 'success') {
            container.innerHTML = `<p class="text-danger text-center">${data.message}</p>`;
            return;
        }

        const orders = data.orders;

        if (!orders.length) {
            container.innerHTML = `<p class="text-warning text-center fs-4">You have not placed any orders yet.</p>`;
            return;
        }

        let html = `<table class="table table-bordered table-hover">
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
            const paymentClass = o.payment_status === 'Paid' ? 'status-Paid' :
                                 o.payment_status === 'COD' ? 'status-COD' : 'status-Default';
            const orderClass = ['Pending','Processing','Completed','Cancelled'].includes(o.order_status) ?
                               `status-${o.order_status}` : 'status-Default';

            const items = o.items.map(it => `${it.product_name} (x${it.quantity})`).join('<br>');

            html += `<tr>
                <td>${idx+1}</td>
                <td>${new Date(o.order_date).toLocaleString()}</td>
                <td><span class="status-badge ${paymentClass}">${o.payment_status}</span></td>
                <td><span class="status-badge ${orderClass}">${o.order_status}</span></td>
                <td>${items}</td>
                <td>₹${o.total.toFixed(2)}</td>
            </tr>`;
        });

        html += `</tbody></table>`;
        container.innerHTML = html;

    } catch (err) {
        container.innerHTML = `<p class="text-danger text-center">Failed to load orders. Try again later.</p>`;
        console.error(err);
    }
});
