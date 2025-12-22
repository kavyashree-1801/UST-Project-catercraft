<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username   = $_SESSION['name'] ?? 'User';
$cart_count = array_sum($_SESSION['cart'] ?? []);

$sql    = "SELECT * FROM products";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | CaterCraft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/product.css">
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">

        <a class="navbar-brand" href="homepage.php">CaterCraft</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="homepage.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="feedback.php">Feedback</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="products.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_orders.php">My Orders</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-2">
                    <span class="nav-link text-warning">
                        Hello, <?= htmlspecialchars($username) ?>
                    </span>
                </li>

                <li class="nav-item me-2">
                    <a href="profile.php" class="btn btn-info btn-sm">Profile</a>
                </li>
            </ul>

        </div>
    </div>

    <!-- Cart -->
    <li class="nav-item position-relative">
        <button class="btn btn-danger" id="cartBtn">
            Cart <span id="cartBadge"><?= $cart_count ?></span>
        </button>


        <div id="cartDropdown" class="cart-dropdown">
            <div id="cartItemsContainer">Loading...</div>
        </div>
    </li>

    <li class="nav-item">
        <a href="logout.php" class="btn btn-danger btn-sm logout-btn">Logout</a>
    </li>

</nav>

<!-- Products Grid -->
<div class="container mt-5 pt-5">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

                <div class="col">
                    <div class="card product-card h-100">

                        <img src="<?= htmlspecialchars($row['image']) ?>" class="card-img-top">

                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title">
                                <?= htmlspecialchars($row['name']) ?>
                            </h5>

                            <p class="card-text">
                                <?= htmlspecialchars($row['description']) ?>
                            </p>

                            <p class="text-danger fw-bold">
                                ₹<?= number_format($row['price'], 2) ?>
                            </p>

                            <button class="btn btn-primary add-to-cart-btn" data-id="<?= $row['id'] ?>">
                                Add to Cart
                            </button>
                        </div>

                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-center">No products found.</p>
        <?php endif; ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/product.js"></script>

</body>
</html>
