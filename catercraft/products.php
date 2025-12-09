<?php
session_start();
include 'config.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// User info
$username = $_SESSION['name'] ?? 'User';
$profile_pic = $_SESSION['profile_pic'] ?? 'images/default-avatar.png';

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1;
    } else {
        $_SESSION['cart'][$product_id] += 1;
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $con->query($sql);

// Fetch cart details
$cart_products = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $cart_sql = "SELECT * FROM products WHERE id IN ($ids)";
    $cart_result = $con->query($cart_sql);
    while ($row = $cart_result->fetch_assoc()) {
        $row['quantity'] = $_SESSION['cart'][$row['id']];
        $row['subtotal'] = $row['price'] * $row['quantity'];
        $total += $row['subtotal'];
        $cart_products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Food Menu | CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { font-family: Arial, sans-serif; margin-top:70px; background:#f8f8f8; }

/* Navbar link styling and spacing */
.navbar-nav .nav-link {
    font-weight: 700 !important;
    margin: 0 12px; /* space between navbar items */
    font-size: 16px;
}

/* Product cards */
.product-card { height: 100%; display:flex; flex-direction:column; }
.card-body { flex:1 1 auto; display:flex; flex-direction:column; justify-content:space-between; }

/* Cart dropdown */
.cart-dropdown {
    position: absolute;
    right: 0;
    top: 100%;
    background: #fff;
    width: 350px;
    border:1px solid #ddd;
    border-radius:5px;
    display:none;
    z-index:1000;
    max-height:400px;
    overflow-y:auto;
}
.cart-dropdown.active { display:block; }
.cart-item { display:flex; align-items:center; justify-content:space-between; padding:10px; border-bottom:1px solid #eee; }
.cart-item img { width:50px; height:50px; object-fit:cover; border-radius:5px; margin-right:10px; }
.cart-item-details { flex:1; }
.cart-item-details span { display:block; }
.cart-total { text-align:right; font-weight:bold; padding:10px; }
.checkout-btn { width:100%; padding:10px; background:#4caf50; color:white; border:none; border-radius:5px; cursor:pointer; font-weight:bold; margin-top:10px; }
.checkout-btn:hover { background:#45a049; }
</style>

<script>
function toggleCart() {
    document.getElementById('cartDropdown').classList.toggle('active');
}
</script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="homepage.php">CaterCraft</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
        <li class="nav-item"><a class="nav-link active" href="products.php">Menu</a></li>
        <li class="nav-item"><a class="nav-link" href="my_orders.php">My Orders</a></li>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <!-- Username -->
        <li class="nav-item me-2">
            <span class="nav-link text-warning">Hello,<?=htmlspecialchars($username) ?></span>
        </li>

        <!-- Profile Button -->
        <li class="nav-item me-2">
            <a href="profile.php" class="btn btn-info btn-sm">Profile</a>
        </li>

        <!-- Cart -->
        <li class="nav-item position-relative">
            <button class="btn btn-danger" onclick="toggleCart()">
                Cart <span class="badge bg-light text-dark"><?= array_sum($_SESSION['cart'] ?? []) ?></span>
            </button>
            <div id="cartDropdown" class="cart-dropdown">
                <?php if(!empty($cart_products)): ?>
                    <?php foreach($cart_products as $item): ?>
                        <div class="cart-item">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="cart-item-details">
                                <span><?= htmlspecialchars($item['name']) ?></span>
                                <span>Qty: <?= $item['quantity'] ?></span>
                                <span>₹<?= number_format($item['subtotal'],2) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="cart-total">Total: ₹<?= number_format($total,2) ?></div>
                    <form method="post" action="checkout.php">
                        <button type="submit" class="checkout-btn">Checkout</button>
                    </form>
                <?php else: ?>
                    <div style="padding:10px;">Your cart is empty.</div>
                <?php endif; ?>
            </div>
        </li>

        <!-- Logout -->
        <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Products Grid -->
<div class="container mt-4">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col">
                    <div class="card product-card h-100">
                        <img src="<?= htmlspecialchars($row['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']) ?>" style="height:200px; object-fit:cover;">
                        <div class="card-body d-flex flex-column text-center">
                            <h5 class="card-title"><?= htmlspecialchars($row['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($row['description']) ?></p>
                            <p class="text-danger fw-bold">₹<?= number_format($row['price'],2) ?></p>
                            <form method="post" class="mt-auto">
                                <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
                            </form>
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
</body>
</html>
