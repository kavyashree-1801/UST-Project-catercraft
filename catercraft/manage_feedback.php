<?php
session_start();
include 'config.php';

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// -------------------------------
// DELETE FEEDBACK
// -------------------------------
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $con->query("DELETE FROM feedback WHERE id = $id");
    header("Location: manage_feedback.php");
    exit;
}

// -------------------------------
// FETCH FEEDBACK
// -------------------------------
$feedbacks = $con->query("SELECT * FROM feedback ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Feedback - Admin | CaterCraft</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f3f3f3;
}
.table-container {
    margin-top: 100px;
    background: white;
    padding: 20px;
    border-radius: 10px;
}

/* Navbar improvements */
.navbar-nav {
    flex-wrap: nowrap;       /* prevent wrapping */
    overflow-x: auto;        /* horizontal scroll if too long */
}
.navbar-nav .nav-item {
    margin-right: 15px;      /* spacing between links */
}
.navbar-nav .nav-link {
    font-weight: 700;        /* bold */
    text-transform: none;    /* no uppercase */
}
.navbar-brand {
    font-weight: 700;
}
</style>
</head>
<body>

<!-- Admin Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand">CaterCraft</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="adminNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_contact.php">Manage Contacts</a></li>
        <li class="nav-item"><a class="nav-link active" href="manage_feedback.php">Manage Feedback</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_orders.php">Manage Orders</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_products.php">Manage Products</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_payments.php">Manage Payments</a></li>
        <li class="nav-item"><a class="nav-link" href="manage_users.php">Manage Users</a></li>
      </ul>

      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-3">
            <span class="nav-link text-warning fw-bold">
                Hello, <?= htmlspecialchars($_SESSION['name']) ?>
            </span>
        </li>
        <li class="nav-item">
            <a href="logout.php" class="btn btn-danger btn-sm fw-bold">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End Admin Navbar -->

<div class="container table-container">
<h3 class="mb-4 fw-bold">User Feedback</h3>

<?php if ($feedbacks->num_rows > 0): ?>
<table class="table table-bordered table-striped">
<thead class="table-dark">
<tr>
    <th>#</th>
    <th>Name</th>
    <th>Email</th>
    <th>Rating</th>
    <th>Feedback</th>
    <th>Date</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php 
$sn = 1;
while ($row = $feedbacks->fetch_assoc()): ?>
<tr>
    <td><?= $sn++ ?></td>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= intval($row['rating']) ?> ★</td>
    <td><?= nl2br(htmlspecialchars($row['feedback'])) ?></td>
    <td><?= $row['created_at'] ?? 'N/A' ?></td>
    <td>
        <a href="manage_feedback.php?delete=<?= $row['id'] ?>" 
           class="btn btn-danger btn-sm fw-bold"
           onclick="return confirm('Are you sure you want to delete this feedback?')">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p class="text-center">No feedback found.</p>
<?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
