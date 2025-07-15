<?php
session_start();
$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    $query = $conn->prepare("SELECT * FROM users WHERE email=? AND role='trainer' LIMIT 1");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $trainer = $result->fetch_assoc();

    if ($trainer && $trainer['password'] === $password) {
        $_SESSION['user'] = $trainer;
        header("Location: dashboard/trainer_dashboard.php");
        exit();
    } else {
        $error = "❌ Invalid email or password.";
    }

    $query->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Trainer Login - FitZone</title>
  <style>
    body { font-family: sans-serif; background: #f5f6fa; padding: 50px; }
    .box {
      max-width: 400px; margin: auto; background: white; padding: 30px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1); border-radius: 10px;
    }
    h2 { color: #ff3d00; text-align: center; }
    input, button {
      width: 100%; padding: 10px; margin-top: 10px;
      border-radius: 6px; border: 1px solid #ccc;
    }
    button {
      background: #ff3d00; color: white; font-weight: bold; border: none;
    }
    .error { color: red; text-align: center; margin-top: 10px; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Trainer Login</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
  </div>
</body>
</html>
