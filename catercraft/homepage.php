<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'] ?? "User";
$role = $_SESSION['role'] ?? "guest";

// Fetch recent orders for admin
$recentOrders = [];
if ($role === 'admin') {
    $sql = "
        SELECT o.id AS order_id, o.username, o.order_status, od.product_name, od.price
        FROM orders o
        INNER JOIN order_details od ON o.id = od.order_id
        INNER JOIN (
            SELECT username, MAX(id) AS latest_order_id
            FROM orders
            GROUP BY username
        ) AS latest_orders ON o.username = latest_orders.username AND o.id = latest_orders.latest_order_id
        ORDER BY o.id DESC
        LIMIT 5
    ";
    $result = $con->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recentOrders[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CaterCraft - Home</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/homepage.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="homepage.php">CaterCraft</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <?php if($role === 'admin'): ?>
            <li class="nav-item"><a class="nav-link  active" href="homepage.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_contact.php">Manage Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_feedback.php">Manage Feedback</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_payments.php">Manage Payments</a></li>
            <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link active" href="homepage.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
            <li class="nav-item"><a class="nav-link" href="products.php">Menu</a></li>
            <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center">
        <span class="navbar-text orange me-2">Hello, <?= htmlspecialchars($name) ?></span>
        <?php if($role !== 'admin'): ?>
            <a href="profile.php" class="btn btn-info btn-sm me-2">Profile</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero">
    <h1>Welcome to CaterCraft!</h1>
    <p>Delicious rural catering, straight to your events.</p>
    <?php if($role === 'admin'): ?>
        <a href="manage_orders.php" class="btn btn-cta">Go to Dashboard</a>
    <?php else: ?>
        <a href="products.php" class="btn btn-cta">View Menu</a>
    <?php endif; ?>
</section>

<?php if($role === 'admin'): ?>
<!-- ADMIN RECENT ORDERS -->
<section class="container mt-4">
    <h2 class="text-center mb-3">Recent Orders</h2>
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User Name</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recentOrders as $order): ?>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                    <td>₹<?= $order['price'] ?></td>
                    <td><?= ucfirst($order['order_status']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($recentOrders)): ?>
                <tr><td colspan="5">No recent orders found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>

<!-- FEATURE CARDS (VISIBLE TO ALL) -->
<section class="feature-cards container">
    <div class="feature-card"><h3>Traditional Meals</h3><p>Authentic rural Indian dishes</p></div>
    <div class="feature-card"><h3>Sweets & Snacks</h3><p>Home-made sweets & festive items</p></div>
    <div class="feature-card"><h3>Catering Services</h3><p>Bulk orders for events & functions</p></div>
</section>

<!-- POPULAR DISHES (VISIBLE TO ALL) -->
<section class="section bg-light">
    <h2>Popular Dishes</h2>
    <div class="card-horizontal container">
        <div class="card"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSJuJsuNleDeGImYtcmlIRVt1VewpthEy4kuA&s" alt="Dish 1">
            <div class="card-body"><h5>Rural Thali</h5><p>Wholesome traditional Indian thali.</p></div>
        </div>
        <div class="card"><img src="https://media.istockphoto.com/id/1140646840/photo/mother-giving-healthy-vegan-dessert-snacks-to-toddler-child-concept-of-healthy-sweets-for.jpg?s=612x612&w=0&k=20&c=8fV7R3Ic_R7HQBbc4k0MwxPhv28wDCHJDAynWAtSmpM=" alt="Dish 2">
            <div class="card-body"><h5>Sweets Platter</h5><p>Delicious home-made sweets.</p></div>
        </div>
        <div class="card"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTOrCLfX-9vFs15vBBkSU33B-KLrbL65i_uBg&s" alt="Dish 3">
            <div class="card-body"><h5>Snacks Combo</h5><p>Tasty snacks for all age groups.</p></div>
        </div>
    </div>
</section>

<!-- TESTIMONIALS -->
<section class="section">
    <h2>Testimonials</h2>
    <div id="testimonialCarousel" class="carousel slide container" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active"><p class="testimonial">"Exceptional catering service!"</p><p class="testimonial-author">- Rajesh Kumar</p></div>
        <div class="carousel-item"><p class="testimonial">"Highly recommend for rural events."</p><p class="testimonial-author">- Anjali Singh</p></div>
        <div class="carousel-item"><p class="testimonial">"Professional and timely."</p><p class="testimonial-author">- Sunita Sharma</p></div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
      <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>
</section>

<!-- SPECIAL OFFERS -->
<section class="section bg-light">
    <h2>Special Offers</h2>
    <div class="card-horizontal container">
        <div class="offer-card"><h5>Festival Discount</h5><p>15% off on sweets & snacks!</p></div>
        <div class="offer-card"><h5>Bulk Order Offer</h5><p>10% off for orders above ₹5000</p></div>
        <div class="offer-card"><h5>Combo Meal Deal</h5><p>Buy 2 thalis, get 1 free dessert platter</p></div>
    </div>
</section>

<footer>
    &copy; <?= date('Y') ?> CaterCraft. All rights reserved.
    <br><a href="privacy.php">Privacy Policy</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
