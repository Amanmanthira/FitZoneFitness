<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../admin_login.php");
    exit();
}


$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user = $_SESSION['user'];
$msg = "";

// Handle user add/delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add user
    if (isset($_POST['add_user'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password']; // Ideally use password_hash

        // Check if email exists
        $check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $msg = "Email already exists.";
        } else {
            $insert = $conn->prepare("INSERT INTO users (name, email, password, role, membership_plan) VALUES (?, ?, ?, ?, '')");
            $insert->bind_param("ssss", $name, $email, $password, $role);
            $insert->execute();
            if ($insert->affected_rows > 0) {
                $msg = "User added successfully.";
            } else {
                $msg = "Failed to add user.";
            }
            $insert->close();
        }
        $check->close();
    }
    // Delete user
    if (isset($_POST['delete_user'])) {
        $del_id = $_POST['delete_user'];
        if ($del_id != $user['id']) { // Prevent deleting self
            $conn->query("DELETE FROM users WHERE id=$del_id AND role != 'customer'");
            $msg = "User deleted.";
        } else {
            $msg = "You cannot delete yourself.";
        }
    }
    // Approve/reject payment slips
    if (isset($_POST['approve_slip'])) {
        $slip_id = $_POST['approve_slip'];
        $conn->query("UPDATE payment_slips SET status='approved' WHERE id=$slip_id");
        $msg = "Payment slip approved.";
    }
    if (isset($_POST['reject_slip'])) {
        $slip_id = $_POST['reject_slip'];
        $conn->query("UPDATE payment_slips SET status='rejected' WHERE id=$slip_id");
        $msg = "Payment slip rejected.";
    }
}

// Fetch users (admins and gym staff)
$users_res = $conn->query("SELECT id, name, email, role FROM users WHERE role != 'customer' ORDER BY role, name");

// Fetch payment slips (with user info)
$slips_res = $conn->query("SELECT ps.*, u.name as username FROM payment_slips ps JOIN users u ON ps.user_id = u.id ORDER BY ps.created_at DESC");




if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST['add_blog'])) {
    $title = $conn->real_escape_string($_POST['blog_title']);
    $content = $conn->real_escape_string($_POST['blog_content']);
    $conn->query("INSERT INTO blog_posts (title, content) VALUES ('$title', '$content')");
  }

  if (isset($_POST['delete_blog'])) {
    $id = intval($_POST['delete_blog_id']);
    $conn->query("DELETE FROM blog_posts WHERE id=$id");
  }
}
// Add plan
if (isset($_POST['add_plan'])) {
    $name = $_POST['plan_name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $features = $_POST['features'];
    $conn->query("INSERT INTO membership_plans (name, price, duration, features) 
                  VALUES ('$name', '$price', '$duration', '$features')");
    $msg = "Plan added successfully.";
}

// Delete plan
if (isset($_POST['delete_plan'])) {
    $id = $_POST['delete_plan'];
    $conn->query("DELETE FROM membership_plans WHERE id=$id");
    $msg = "Plan deleted.";
}
// Fetch single plan to edit
$edit_plan = null;
if (isset($_GET['edit_plan'])) {
    $edit_id = intval($_GET['edit_plan']);
    $edit_query = $conn->query("SELECT * FROM membership_plans WHERE id = $edit_id LIMIT 1");
    $edit_plan = $edit_query->fetch_assoc();
}

// Handle update
if (isset($_POST['update_plan'])) {
    $id = intval($_POST['plan_id']);
    $name = $_POST['plan_name'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $features = $_POST['features'];

    $conn->query("UPDATE membership_plans SET name='$name', price='$price', duration='$duration', features='$features' WHERE id=$id");
    $msg = "Plan updated successfully.";
}

// Fetch plans
$plans_res = $conn->query("SELECT * FROM membership_plans ORDER BY id DESC");
if (!$plans_res) {
    die("Query Failed: " . $conn->error);
}

$bookings_res = $conn->query("
  SELECT b.id, 
         u1.name AS customer_name, 
         u2.name AS trainer_name, 
         u2.email AS trainer_email,
         b.booked_at
  FROM trainer_bookings b
  JOIN users u1 ON b.customer_id = u1.id
  JOIN users u2 ON b.trainer_id = u2.id
  ORDER BY b.booked_at DESC
");

?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard - FitZone</title>
  <style>
    body {
      margin: 0; font-family: 'Segoe UI', sans-serif;
      display: flex; min-height: 100vh; background: #f5f6fa;
    }
    .sidebar {
      width: 240px; background: #0f2027; color: white; padding: 30px 20px;
      display: flex; flex-direction: column;
      position: fixed; height: 100vh;
    }
    .sidebar h2 {
      color: #ff3d00; margin-bottom: 40px; font-size: 2rem;
      text-align: center;
    }
    .sidebar a {
      color: white; text-decoration: none; padding: 12px 15px;
      margin-bottom: 10px; border-radius: 6px; font-weight: 600;
      cursor: pointer;
    }
    .sidebar a:hover, .sidebar a.active {
      background: #1c1c1c;
    }
    .main {
      margin-left: 260px; padding: 40px; width: 100%;
    }
    h1, h2 {
      color: #ff3d00;
    }
    button {
      background: #ff3d00; border: none; color: white;
      padding: 8px 15px; border-radius: 6px; font-weight: bold;
      cursor: pointer; margin-top: 10px;
      transition: background 0.3s ease;
    }
    button:hover {
      background: #e63600;
    }
    table {
      width: 100%; border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      padding: 12px; border-bottom: 1px solid #ddd;
      text-align: left;
    }
    th {
      background: #222;
      color: white;
    }
    .msg {
      background: #dff0d8; color: #3c763d; padding: 10px;
      border-left: 5px solid #3c763d; margin-bottom: 20px;
      border-radius: 5px;
    }
    .section {
      display: none;
    }
    .section.active {
      display: block;
    }
    input, select {
      padding: 10px; margin: 8px 0; width: 100%; border-radius: 6px; border: 1px solid #ccc;
    }
    label {
      font-weight: 600;
    }
    .actions button {
      margin-right: 10px;
    }
  </style>
  <script>
    function showSection(id) {
      document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
      document.getElementById(id).classList.add('active');
      document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
      document.querySelector('[data-section="'+id+'"]').classList.add('active');
    }
    window.onload = () => showSection('dashboard');
  </script>
</head>
<body>

<div class="sidebar">
  <h2>FitZone Admin</h2>
  <a href="#" data-section="dashboard" onclick="showSection('dashboard')">Dashboard</a>
  <a href="#" data-section="manage_users" onclick="showSection('manage_users')">Manage Users</a>
  <a href="#" data-section="payment_slips" onclick="showSection('payment_slips')">Payment Slips</a>
  <a onclick="showSection('manage_blogs')">Manage Blogs</a>
  <a href="#" data-section="manage_plans" onclick="showSection('manage_plans')">Membership Plans</a>
  <a href="#" data-section="trainer_bookings" onclick="showSection('trainer_bookings')">Trainer Bookings</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">
  <?php if ($msg): ?>
    <div class="msg"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <section id="dashboard" class="section">
    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
    <p>Use the sidebar to manage users and view payment slips.</p>
  </section>

  <section id="manage_users" class="section">
    <h2>Manage Users</h2>

    <form method="POST" style="max-width: 400px;">
      <h3>Add New User</h3>
      <label>Name</label>
      <input type="text" name="name" required>
      <label>Email</label>
      <input type="email" name="email" required autocomplete="off">
      <label>Password</label>
      <input type="password" name="password" required>
      <label>Role</label>
      <select name="role" required>
        <option value="">-- Select Role --</option>
        <option value="admin">admin</option>
        <option value="gym_staff">trainer</option>
      </select>
      <button type="submit" name="add_user">Add User</button>
    </form>

    <h3>Existing Users</h3>
    <table>
      <thead>
        <tr><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php while ($row = $users_res->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['role']); ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <button type="submit" name="delete_user" value="<?php echo $row['id']; ?>" onclick="return confirm('Delete this user?')">Delete</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </section>




  <section id="manage_blogs" class="section">
  <div class="form-box">
    <h2>✍️ Add New Blog Post</h2>
    <form method="POST">
      <input type="text" name="blog_title" placeholder="Blog Title" required>
      <textarea name="blog_content" placeholder="Blog Content" rows="6" required></textarea>
      <button type="submit" name="add_blog">Add Blog</button>
    </form>
  </div>

  <div class="card">
    <h2>📰 All Blog Posts</h2>
    <?php
$blogs = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC") or die("Query Failed: " . $conn->error);
      while ($blog = $blogs->fetch_assoc()) {
        echo "<div class='form-box'>";
        echo "<h3>" . htmlspecialchars($blog['title']) . "</h3>";
        echo "<p>" . nl2br(htmlspecialchars($blog['content'])) . "</p>";
        echo "<small>Posted on: " . $blog['created_at'] . "</small><br>";
        echo "<form method='POST' style='margin-top:10px;'>
                <input type='hidden' name='delete_blog_id' value='{$blog['id']}'>
                <button type='submit' name='delete_blog' style='background:#e74c3c;'>Delete</button>
              </form>";
        echo "</div>";
      }
    ?>
  </div>
</section>

  <section id="payment_slips" class="section">
    <h2>Payment Slips</h2>
    <?php if ($slips_res->num_rows > 0): ?>
      <table>
        <thead>
          <tr><th>User</th><th>Month</th><th>Slip</th><th>Uploaded At</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          <?php while ($slip = $slips_res->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($slip['username']); ?></td>
              <td><?php echo htmlspecialchars($slip['month']); ?></td>
              <td>
                <?php 
                  $file = $slip['file_path'];
                  $ext = pathinfo($file, PATHINFO_EXTENSION);
                  if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png'])) {
                    echo "<img src='$file' style='width:80px; border-radius:5px;' alt='Slip'>";
                  } else {
                    echo "<a href='$file' target='_blank'>View Slip</a>";
                  }
                ?>
              </td>
              <td><?php echo $slip['created_at']; ?></td>
              <td><?php echo ucfirst($slip['status'] ?? 'pending'); ?></td>
              <td class="actions">
                <?php if (($slip['status'] ?? 'pending') === 'pending'): ?>
                  <form method="POST" style="display:inline;">
                    <button type="submit" name="approve_slip" value="<?php echo $slip['id']; ?>">Approve</button>
                  </form>
                  <form method="POST" style="display:inline;">
                    <button type="submit" name="reject_slip" value="<?php echo $slip['id']; ?>">Reject</button>
                  </form>
                <?php else: ?>
                  <em>No actions</em>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No payment slips uploaded yet.</p>
    <?php endif; ?>
  </section>

  <section id="manage_plans" class="section">
  <h2>💰 Manage Membership Plans</h2>

  <form method="POST" style="max-width: 500px;">
  <h3><?php echo $edit_plan ? "✏️ Edit Plan" : "➕ Add New Plan"; ?></h3>
  <input type="hidden" name="plan_id" value="<?php echo $edit_plan['id'] ?? ''; ?>">

  <input type="text" name="plan_name" placeholder="Plan Name" required
         value="<?php echo $edit_plan['name'] ?? ''; ?>">

  <input type="number" name="price" placeholder="Price (LKR)" step="0.01" required
         value="<?php echo $edit_plan['price'] ?? ''; ?>">

  <input type="text" name="duration" placeholder="Duration" required
         value="<?php echo $edit_plan['duration'] ?? ''; ?>">

  <textarea name="features" placeholder="Enter features (1 per line)" rows="4"><?php echo $edit_plan['features'] ?? ''; ?></textarea>

  <?php if ($edit_plan): ?>
    <button type="submit" name="update_plan">Update Plan</button>
    <a href="admin_dashboard.php" style="margin-left:10px; text-decoration:none;">Cancel</a>
  <?php else: ?>
    <button type="submit" name="add_plan">Add Plan</button>
  <?php endif; ?>
</form>



  <h3 style="margin-top:40px;">All Plans</h3>
  <table>
    <thead>
      <tr>
        <th>Name</th><th>Price</th><th>Duration</th><th>Features</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($plan = $plans_res->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($plan['name']); ?></td>
        <td>LKR <?php echo number_format($plan['price'], 2); ?></td>
        <td><?php echo htmlspecialchars($plan['duration']); ?></td>
        <td><?php echo nl2br(htmlspecialchars($plan['features'])); ?></td>
        <td>
  <form method="POST" style="display:inline;">
    <button type="submit" name="delete_plan" value="<?php echo $plan['id']; ?>"
      onclick="return confirm('Are you sure to delete this plan?')">Delete</button>
  </form>
  <a href="admin_dashboard.php?edit_plan=<?php echo $plan['id']; ?>" style="margin-left:10px;">
    ✏️ Edit
  </a>
</td>

        

      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</section>

<section id="trainer_bookings" class="section">
  <h2>🤝 Trainer Bookings</h2>

  <?php if ($bookings_res && $bookings_res->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Customer</th>
          <th>Trainer</th>
          <th>Trainer Email</th>
          <th>Booked At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($b = $bookings_res->fetch_assoc()): ?>
        <tr>
          <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
          <td><?php echo htmlspecialchars($b['trainer_name']); ?></td>
          <td><a href="mailto:<?php echo $b['trainer_email']; ?>"><?php echo htmlspecialchars($b['trainer_email']); ?></a></td>
          <td><?php echo $b['booked_at']; ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <button type="submit" name="delete_booking" value="<?php echo $b['id']; ?>" onclick="return confirm('Delete this booking?')">Delete</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No bookings found.</p>
  <?php endif; ?>
</section>

</div>

</body>
</html>
<?php
$conn->close();
?>