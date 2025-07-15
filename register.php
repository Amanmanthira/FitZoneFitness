<?php
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DB connect
    $conn = new mysqli("localhost", "root", "", "fitzone");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $conn->real_escape_string($_POST["name"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $plan = $_POST["membership_plan"];

    $check = $conn->query("SELECT id FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already exists!";
    } else {
        $sql = "INSERT INTO users (name, email, password, role, membership_plan)
                VALUES ('$name', '$email', '$password', 'customer', '$plan')";
        if ($conn->query($sql)) {
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Something went wrong.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer Sign Up</title>
  <style>
    body {
      background: #1e1f24; color: white; font-family: 'Segoe UI', sans-serif;
      display: flex; align-items: center; justify-content: center; height: 100vh;
    }
    .form-box {
      background: #2a2c31; padding: 30px; border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3); width: 100%; max-width: 450px;
    }
    input, select, button {
      width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px;
      border: none; font-size: 1rem;
    }
    input, select {
      background: #3c3f47; color: white;
    }
    button {
      background: #ff3d00; color: white; font-weight: bold; cursor: pointer;
    }
    .message {
      text-align: center; font-size: 0.9rem;
    }
    .error { color: #ff5252; }
    .success { color: #00e676; }
  </style>
</head>
<body>

<div class="form-box">
  <h2 style="text-align:center;">Customer Sign Up</h2>
  <form method="POST" action="">
    <input type="text" name="name" placeholder="Full Name" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <select name="membership_plan" required>
      <option value="">Select Membership</option>
      <option value="Basic">Basic</option>
      <option value="Premium">Premium</option>
      <option value="Gold">Gold</option>
    </select>
    <button type="submit">Sign Up</button>
    <p class="message success"><?php echo $success; ?></p>
    <p class="message error"><?php echo $error; ?></p>
    <p class="message">Already registered? <a href="login.php" style="color: #00e5cf;">Login here</a></p>
  </form>
</div>

</body>
</html>
