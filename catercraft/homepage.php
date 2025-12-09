<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION['name'] ?? "User";
$role = $_SESSION['role'] ?? "guest";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CaterCraft - Home</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/home.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    
    <!-- Brand -->
    <a class="navbar-brand" href="homepage.php">CaterCraft</a>

    <!-- Centered Links -->
    <div class="mx-auto">
      <ul class="navbar-nav d-flex flex-row justify-content-center">
        <?php if($role === 'admin'): ?>
            <li class="nav-item mx-2"><a class="nav-link" href="homepage.php">Home</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="manage_contact.php">Manage Contact</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="manage_feedback.php">Manage Feedback</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
        <?php else: ?>
            <li class="nav-item mx-2"><a class="nav-link" href="homepage.php">Home</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="contact.php">Contact</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="feedback.php">Feedback</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="products.php">Menu</a></li>
            <li class="nav-item mx-2"><a class="nav-link" href="my_orders.php">My Orders</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Right Buttons -->
    <div class="d-flex align-items-center">
      <span class="navbar-text text-white me-3">
        Hello, <?= htmlspecialchars($name) ?> (<?= $role ?>)
      </span>
      <?php if($role !== 'admin'): ?>
        <a href="profile.php" class="btn btn-info btn-sm me-2">Profile</a>
      <?php endif; ?>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>

  </div>
</nav>

<!-- ================= BODY ================= -->
<?php if($role === 'admin'): ?>

    <div class="admin-hero">
        <h1>Welcome, <?= htmlspecialchars($name) ?>!</h1>
        <p>Oversee CaterCraft operations and monitor system performance</p>
    </div>

    <div class="container">
        <div class="admin-cards">
            <div class="admin-card">
                <h3>👥 Users</h3>
                <p>Monitor all users and their activity</p>
            </div>
            <div class="admin-card">
                <h3>📦 Orders</h3>
                <p>Review recent orders and their status</p>
            </div>
            <div class="admin-card">
                <h3>🍽️ Products</h3>
                <p>Manage menu items, availability, and updates</p>
            </div>
            <div class="admin-card">
                <h3>📞 Contacts</h3>
                <p>Check incoming messages and inquiries</p>
            </div>
            <div class="admin-card">
                <h3>💬 Feedback</h3>
                <p>Monitor user feedback and reviews</p>
            </div>
        </div>
    </div>

<?php else: ?>

    <div class="user-hero">
        <h1>Welcome, <?= htmlspecialchars($name) ?>!</h1>
        <p>Explore our delicious food menu and enjoy the best rural catering service</p>
    </div>

    <div class="container categories">
        <div class="category-card">
            <h4>Traditional Meals</h4>
            <p>Authentic rural Indian dishes.</p>
        </div>
        <div class="category-card">
            <h4>Sweets & Snacks</h4>
            <p>Home-made sweets & festive items.</p>
        </div>
        <div class="category-card">
            <h4>Catering Services</h4>
            <p>Bulk orders for functions & events.</p>
        </div>
    </div>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
