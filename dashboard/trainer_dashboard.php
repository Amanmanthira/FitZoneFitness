<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'trainer') {
    header("Location: ../trainer_login.php");
    exit();
}

$user = $_SESSION['user'];

$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Customers who booked this trainer
$bookings = $conn->query("
  SELECT u.name AS customer_name, u.email, b.booked_at
  FROM trainer_bookings b
  JOIN users u ON b.customer_id = u.id
  WHERE b.trainer_id = " . $user['id'] . "
  ORDER BY b.booked_at DESC
");

// All customers (not necessarily booked)
$all_customers = $conn->query("SELECT name, email, membership_plan FROM users WHERE role = 'customer' ORDER BY name");

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Trainer Dashboard - FitZone</title>
  <style>
    body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f9f9f9; }
    .sidebar {
      position: fixed; left: 0; top: 0; bottom: 0; width: 220px;
      background: #0f2027; color: white; padding: 30px 20px;
    }
    .sidebar h2 { font-size: 1.5rem; margin-bottom: 30px; }
    .sidebar a {
      display: block; color: white; text-decoration: none;
      margin: 15px 0; font-weight: bold; padding: 10px; border-radius: 6px;
    }
    .sidebar a:hover { background: #1c1c1c; }

    .main {
      margin-left: 240px; padding: 40px;
    }

    .card {
      background: white; border-radius: 10px; padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05); margin-bottom: 30px;
    }

    h2 { color: #ff3d00; margin-bottom: 20px; }
    table {
      width: 100%; border-collapse: collapse;
    }
    th, td {
      padding: 12px; text-align: left;
      border-bottom: 1px solid #ddd;
    }
    th {
      background: #333; color: white;
    }
  </style>
  <script>
    function showSection(id) {
      const sections = document.querySelectorAll('.section');
      sections.forEach(s => s.style.display = 'none');
      document.getElementById(id).style.display = 'block';
    }
    window.onload = () => showSection('profile');
  </script>
</head>
<body>

<div class="sidebar">
  <h2>Trainer Panel</h2>
  <a href="#" onclick="showSection('profile')">My Profile</a>
  <a href="#" onclick="showSection('booked_customers')">Booked Customers</a>
  <a href="#" onclick="showSection('all_customers')">All Customers</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">
  <section id="profile" class="section">
    <div class="card">
      <h2>👋 Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Role:</strong> Trainer</p>
    </div>
  </section>

  <section id="booked_customers" class="section" style="display:none;">
    <div class="card">
      <h2>📋 Customers Who Booked You</h2>
      <?php if ($bookings && $bookings->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Booked At</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($b = $bookings->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
            <td><?php echo htmlspecialchars($b['email']); ?></td>
            <td><?php echo $b['booked_at']; ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>No bookings yet.</p>
      <?php endif; ?>
    </div>
  </section>

  <section id="all_customers" class="section" style="display:none;">
    <div class="card">
      <h2>👥 All Customers</h2>
      <?php if ($all_customers->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Membership Plan</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($c = $all_customers->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($c['name']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?></td>
            <td><?php echo htmlspecialchars($c['membership_plan']); ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>No customers found.</p>
      <?php endif; ?>
    </div>
  </section>
</div>

</body>
</html>
