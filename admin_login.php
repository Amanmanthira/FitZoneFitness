<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            if ($user['role'] === 'admin') {
                header("Location: dashboard/admin_dashboard.php");
            } else if ($user['role'] === 'gym_staff') {
                header("Location: dashboard/staff_dashboard.php");
            } else {
                header("Location: dashboard/customer_dashboard.php");
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Admin Login - FitZone</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #222; color: white; display: flex; justify-content: center; align-items: center; height: 100vh; }
    .login-box { background: #333; padding: 30px; border-radius: 10px; width: 320px; }
    h2 { margin-bottom: 20px; color: #ff3d00; }
    input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 6px; border: none; }
    button { width: 100%; padding: 12px; background: #ff3d00; border: none; border-radius: 6px; color: white; font-weight: bold; cursor: pointer; }
    button:hover { background: #e63600; }
    .msg { background: #ff4444; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Admin Login</h2>
    <?php if ($msg) echo "<div class='msg'>$msg</div>"; ?>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" required autocomplete="off" />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
