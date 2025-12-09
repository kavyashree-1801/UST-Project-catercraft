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

<style>
/* ---------------------- BODY & GENERAL ---------------------- */
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    min-height: 100vh;
    background: #f5f5f5;
}

/* ---------------------- NAVBAR (BOLD ONLY, NO CAPITALS) ---------------------- */
.navbar-brand {
    font-weight: 800;
    font-size: 26px;
    letter-spacing: 0.3px;
}

.navbar-nav .nav-link {
    font-weight: 700 !important;
    font-size: 16px;
}

.navbar-text {
    font-weight: 600;
}

/* ---------------------- ADMIN DASHBOARD ---------------------- */
.admin-hero {
    height: 60vh;
    background: url('https://img.freepik.com/free-photo/side-view-people-celebrating-tamil-new-year_23-2151210764.jpg?semt=ais_hybrid&w=740&q=80') no-repeat center center/cover;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: relative;
}
.admin-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right:0; bottom:0;
    background-color: rgba(0,0,0,0.5);
}
.admin-hero h1, .admin-hero p {
    position: relative;
    z-index: 1;
}
.admin-hero h1 {
    font-size: 48px;
    margin-bottom: 15px;
}
.admin-hero p {
    font-size: 20px;
}

/* Admin cards */
.admin-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin: 40px 0;
}
.admin-card {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.admin-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.admin-card h3 {
    margin-bottom: 10px;
    color: #ff7e5f;
}
.admin-card p {
    font-size: 14px;
    color: #555;
}

/* ---------------------- USER HOMEPAGE ---------------------- */
.user-hero {
    height: 60vh;
    background: url('https://5.imimg.com/data5/ANDROID/Default/2025/5/513279718/GA/KN/QY/162752310/product-jpeg-500x500.jpg') no-repeat center center/cover;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    text-align: center;
    flex-direction: column;
}
.user-hero h1 {
    font-size: 48px;
    margin-bottom: 15px;
}
.user-hero p {
    font-size: 20px;
}

/* Categories */
.categories {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    margin: 40px 0;
}
.category-card {
    flex: 1 1 250px;
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.category-card h4 {
    margin-bottom: 10px;
    color: #ff7e5f;
}
.category-card p {
    font-size: 14px;
    color: #555;
}
</style>
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
