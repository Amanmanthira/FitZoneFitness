<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'customer') {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];

$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_plan'])) {
    $newPlan = $_POST['membership_plan'];
    $userId = $user['id'];
    $conn->query("UPDATE users SET membership_plan='$newPlan' WHERE id=$userId");
    $_SESSION['user']['membership_plan'] = $newPlan;
    $user['membership_plan'] = $newPlan;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_slip'])) {
    $filename = $_FILES['slip']['name'];
    $tmpname = $_FILES['slip']['tmp_name'];
    $month = $_POST['month'];
    $folder = "uploads/" . uniqid() . "_" . basename($filename);

    if (move_uploaded_file($tmpname, $folder)) {
        $uid = $user['id'];
        $stmt = $conn->prepare("INSERT INTO payment_slips (user_id, file_path, month) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $uid, $folder, $month);
        $stmt->execute();
        $msg = "Slip uploaded successfully.";
    } else {
        $msg = "Upload failed.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_trainer'])) {
    echo "<pre>Booking POST block triggered!</pre>"; // DEBUG
    $trainerId = intval($_POST['book_trainer_id']);
    $customerId = $user['id'];

    $check = $conn->prepare("SELECT id FROM trainer_bookings WHERE trainer_id = ? AND customer_id = ?");
    $check->bind_param("ii", $trainerId, $customerId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "You have already booked this trainer.";
    } else {
        $stmt = $conn->prepare("INSERT INTO trainer_bookings (trainer_id, customer_id, booked_at) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $trainerId, $customerId);
        if ($stmt->execute()) {
            $msg = "Trainer booked successfully!";
        } else {
            $msg = "Booking failed: " . $stmt->error;
        }
        $stmt->close();
    }
    $check->close();
}



$slips = $conn->query("SELECT * FROM payment_slips WHERE user_id = " . $user['id'] . " ORDER BY created_at DESC");
$trainers = $conn->query("SELECT id, name, email FROM users WHERE role = 'trainer'");
$bookings = $conn->query("
    SELECT tb.booked_at, u.name, u.email 
    FROM trainer_bookings tb 
    JOIN users u ON tb.trainer_id = u.id 
    WHERE tb.customer_id = {$user['id']} 
    ORDER BY tb.booked_at DESC
");

// Fetch booked trainer IDs for current user
$bookedTrainerIds = [];
$result = $conn->query("SELECT trainer_id FROM trainer_bookings WHERE customer_id = {$user['id']}");
while ($row = $result->fetch_assoc()) {
    $bookedTrainerIds[] = $row['trainer_id'];
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - FitZone</title>
  <style>
    body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f5f6fa; }
    .sidebar {
      position: fixed; top: 0; left: 0; width: 220px; height: 100vh;
      background: #0f2027; color: white; padding: 30px 20px;
    }
    .sidebar h2 { font-size: 1.5rem; margin-bottom: 30px; }
    .sidebar a {
      display: block; color: white; text-decoration: none;
      margin: 15px 0; font-weight: bold; padding: 10px; border-radius: 6px;
      cursor: pointer;
    }
    .sidebar a:hover { background: #1c1c1c; }
    .main {
      margin-left: 240px; padding: 40px; 
    }
    .card, .form-box {
      background: white; border-radius: 10px; padding: 25px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 30px;
    }
    h2 { color: #ff3d00; margin-bottom: 20px; }
    input, select, button {
      width: 100%; padding: 10px; margin-top: 10px; border-radius: 6px; border: 1px solid #ccc;
    }
    button {
      background: #ff3d00; color: white; font-weight: bold; border: none; cursor: pointer;
      transition: background 0.3s;
    }
    button:hover { background: #e63600; }
    .slip-img { width: 100%; max-width: 300px; border-radius: 6px; margin-top: 10px; }
    .msg { background: #dff0d8; padding: 10px; margin-top: 10px; border-left: 4px solid green; }
    .section { display: none; }
    .section.active { display: block; }
  </style>
  <script>
    function showSection(id) {
      const sections = document.querySelectorAll('.section');
      sections.forEach(sec => sec.classList.remove('active'));
      document.getElementById(id).classList.add('active');
    }
    window.onload = () => showSection('profile');
  </script>
</head>
<body>

<div class="sidebar">
  <h2>FitZone</h2>
  <a onclick="showSection('profile')">Dashboard</a>
  <a onclick="showSection('membership')">Membership</a>
  <a onclick="showSection('upload')">Upload Slip</a>
  <a onclick="showSection('history')">Slip History</a>
  <a onclick="showSection('trainers')">Trainers</a>
  <a onclick="showSection('my_bookings')">My Bookings</a>
  <a href="logout.php">Logout</a>
</div>

<div class="main">
  <section id="profile" class="section">
    <div class="card">
      <h2>👋 Welcome, <?php echo $user['name']; ?></h2>
      <p>Email: <?php echo $user['email']; ?></p>
      <p>Current Plan: <strong><?php echo $user['membership_plan']; ?></strong></p>
    </div>
  </section>

  <section id="membership" class="section">
    <div class="form-box">
      <h2>Update Membership</h2>
      <form method="POST">
        <select name="membership_plan" required>
          <option value="">-- Select Plan --</option>
          <option value="Basic">Basic</option>
          <option value="Premium">Premium</option>
          <option value="Gold">Gold</option>
        </select>
        <button type="submit" name="update_plan">Update</button>
      </form>
    </div>
  </section>

  <section id="upload" class="section">
    <div class="form-box">
      <h2>Upload Payment Slip</h2>
      <form method="POST" enctype="multipart/form-data">
        <label for="month">Select Month</label>
        <select name="month" required>
          <option value="">-- Select Month --</option>
          <?php
            foreach (range(1, 12) as $m) {
              $monthName = date("F", mktime(0, 0, 0, $m, 10));
              echo "<option value='$monthName'>$monthName</option>";
            }
          ?>
        </select>
        <input type="file" name="slip" accept="image/*,.pdf" required>
        <button type="submit" name="upload_slip">Upload</button>
      </form>
      <?php if (isset($msg)) echo "<p class='msg'>$msg</p>"; ?>
    </div>
  </section>

  <section id="history" class="section">
    <div class="card">
      <h2>Your Slips</h2>
      <?php if ($slips->num_rows > 0): ?>
        <?php while ($row = $slips->fetch_assoc()): ?>
          <div class="form-box">
            <p>Month: <strong><?php echo $row['month']; ?></strong></p>
            <p>Uploaded: <?php echo $row['created_at']; ?></p>
            <?php 
              $file = $row['file_path']; 
              $ext = pathinfo($file, PATHINFO_EXTENSION); 
              if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                echo "<img src='$file' class='slip-img'>";
              } else {
                echo "<a href='$file' target='_blank'>View PDF Slip</a>";
              }
            ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No slips uploaded yet.</p>
      <?php endif; ?>
    </div>
  </section>


  <section id="trainers" class="section">
  <div class="card">
    <h2>🏋️ Available Trainers</h2>
    <?php if ($trainers && $trainers->num_rows > 0): ?>
      <table style="width:100%; border-collapse: collapse;">
        <thead>
          <tr style="background:#222; color:white;">
            <th style="padding:10px;">Name</th>
            <th style="padding:10px;">Email</th>
            <th style="padding:10px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while($t = $trainers->fetch_assoc()): ?>
            <tr>
              <td style="padding:10px;"><?php echo htmlspecialchars($t['name']); ?></td>
              <td style="padding:10px;"><?php echo htmlspecialchars($t['email']); ?></td>
              <td style="padding:10px;">
                <a href="mailto:<?php echo $t['email']; ?>?subject=Training%20Request&body=Hi%20<?php echo urlencode($t['name']); ?>,%0AI%20would%20like%20to%20book%20a%20training%20session." style="color:#ff3d00; font-weight:bold;">Send Mail</a>
                |
               <?php if (in_array($t['id'], $bookedTrainerIds)): ?>
  <span style="color: #888;">Already Booked</span>
<?php else: ?>
 <form method="POST" style="display:inline;">
  <input type="hidden" name="book_trainer_id" value="4">
  <button type="submit" name="book_trainer">Book</button>
</form>

<?php endif; ?>

              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No trainers found.</p>
    <?php endif; ?>
  </div>
</section>
<section id="my_bookings" class="section">
  <div class="card">
    <h2>📅 My Trainer Bookings</h2>

    <?php if ($bookings && $bookings->num_rows > 0): ?>
      <table style="width:100%; border-collapse: collapse;">
        <thead>
          <tr style="background:#222; color:white;">
            <th style="padding:10px;">Trainer Name</th>
            <th style="padding:10px;">Email</th>
            <th style="padding:10px;">Booked At</th>
          </tr>
        </thead>
        <tbody>
          <?php while($b = $bookings->fetch_assoc()): ?>
            <tr>
              <td style="padding:10px;"><?php echo htmlspecialchars($b['name']); ?></td>
              <td style="padding:10px;"><?php echo htmlspecialchars($b['email']); ?></td>
              <td style="padding:10px;"><?php echo date("Y-m-d H:i A", strtotime($b['booked_at'])); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No bookings made yet.</p>
    <?php endif; ?>
  </div>
</section>

</div>

</body>
</html>
<script>
  window.onload = () => {
    const redirectToBookings = <?php echo isset($_SESSION['redirect_to_bookings']) ? 'true' : 'false'; ?>;
    if (redirectToBookings) {
      showSection('my_bookings');
      <?php unset($_SESSION['redirect_to_bookings']); ?>
    } else {
      showSection('profile');
    }
  };
</script>

