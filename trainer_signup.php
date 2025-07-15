<?php
$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = $conn->real_escape_string($_POST["password"]);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "❌ Email already registered.";
    } else {
        $role = 'trainer';
        $query = $conn->prepare("INSERT INTO users (name, email, password, role, membership_plan) VALUES (?, ?, ?, ?, '')");
        $query->bind_param("ssss", $name, $email, $password, $role);
        if ($query->execute()) {
            $msg = "✅ Trainer account created. You can now <a href='trainer_login.php'>Login here</a>";
        } else {
            $msg = "❌ Signup failed.";
        }
        $query->close();
    }

    $check->close();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Trainer Signup - FitZone</title>
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
    .msg { margin-top: 10px; color: green; font-weight: bold; text-align: center; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Trainer Signup</h2>
    <form method="POST">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Create Account</button>
    </form>
    <?php if ($msg) echo "<p class='msg'>$msg</p>"; ?>
  </div>
</body>
</html>
