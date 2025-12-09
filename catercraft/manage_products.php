<?php
session_start();
include 'config.php';

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

/* ----------------------------------------
   FETCH USERS (for uploaded_by name)
---------------------------------------- */
$users = [];
$userQuery = $con->query("SELECT id, name FROM users");
if ($userQuery) {
    while ($u = $userQuery->fetch_assoc()) {
        $users[$u['id']] = $u['name'];
    }
}

/* ----------------------------------------
   ADD PRODUCT
---------------------------------------- */
if (isset($_POST['add_product'])) {
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);
    $category    = trim($_POST['category']);
    $uploaded_by = $_SESSION['user_id'];
    $created_at  = date("Y-m-d H:i:s");

    $image = trim($_POST['image']); // URL from input

    if ($name === "" || $price === "" || $image === "" || $category === "") {
        $error = "Please fill all required fields!";
    } else {
        $stmt = $con->prepare("INSERT INTO products (name, description, price, image, uploaded_by, created_at, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssss", $name, $description, $price, $image, $uploaded_by, $created_at, $category);

        if ($stmt->execute()) {
            $success = "Product added successfully!";
        } else {
            $error = "Database error: " . $stmt->error;
        }
    }
}

/* ----------------------------------------
   DELETE PRODUCT
---------------------------------------- */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Product deleted successfully!";
    } else {
        $error = "Failed to delete product.";
    }
}

/* ----------------------------------------
   FETCH PRODUCTS
---------------------------------------- */
$products = $con->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Products - CaterCraft Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: url('https://media.istockphoto.com/id/1198623553/photo/restaurant-chef-serving-food.jpg?s=612x612&w=0&k=20&c=PQzI3_v0zDhm6N2zV0EHT6r4OQx6uF4mCM3A8IhSKdQ=') 
    no-repeat center center/cover;
    min-height: 100vh;
}
.table-container {
    margin-top: 100px;
    background: rgba(255,255,255,0.93);
    padding: 25px;
    border-radius: 15px;
}
.product-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
}

/* Navbar styling */
.navbar-brand,
.navbar .nav-link,
.navbar-text {
    font-weight: bold;
}

/* Add space between navbar links */
.navbar-nav .nav-item {
    margin-right: 20px; /* Adjust spacing as needed */
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand">CaterCraft</a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_contact.php">Manage Contact</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_feedback.php">Manage Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_products.php">Manage Products</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
      </ul>
      <span class="navbar-text text-warning me-3">Hello, <?= htmlspecialchars($_SESSION['name']) ?></span>
      <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container table-container">

<h2 class="text-center mb-4">Manage Products</h2>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<!-- ADD PRODUCT FORM -->
<div class="card mb-4">
  <div class="card-header bg-primary text-white">Add Product</div>
  <div class="card-body">
    <form method="POST">
      <label>Product Name</label>
      <input type="text" name="name" class="form-control mb-2" required>

      <label>Description</label>
      <textarea name="description" class="form-control mb-2" required></textarea>

      <label>Price (₹)</label>
      <input type="number" step="0.01" name="price" class="form-control mb-2" required>

      <label>Category</label>
      <input type="text" name="category" class="form-control mb-2" required>

      <label>Image URL</label>
      <input type="text" name="image" class="form-control mb-3" placeholder="Paste image link here" required>

      <button type="submit" name="add_product" class="btn btn-success">Add Product</button>
    </form>
  </div>
</div>

<!-- PRODUCTS TABLE -->
<table class="table table-bordered table-hover">
<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Description</th>
    <th>₹ Price</th>
    <th>Category</th>
    <th>Image</th>
    <th>Uploaded By</th>
    <th>Created At</th>
    <th>Action</th>
</tr>
</thead>

<tbody>
<?php 
$sn = 1; 
while ($row = $products->fetch_assoc()) { ?>
<tr>
    <td><?= $sn++ ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>
    <td>₹<?= number_format($row['price'],2) ?></td>
    <td><?= htmlspecialchars($row['category']) ?></td>
    <td>
        <?php if ($row['image']) { ?>
            <img src="<?= htmlspecialchars($row['image']) ?>" class="product-img" alt="Product">
        <?php } else { echo "No Image"; } ?>
    </td>
    <td><?= $users[$row['uploaded_by']] ?? 'Unknown' ?></td>
    <td><?= $row['created_at'] ?></td>
    <td>
        <a class="btn btn-danger btn-sm"
           href="manage_products.php?delete=<?= $row['id'] ?>"
           onclick="return confirm('Delete product?')">Delete</a>
    </td>
</tr>
<?php } ?>
</tbody>
</table>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
