<?php
session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DB connection
    $conn = new mysqli("localhost", "root", "", "fitzone");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email' AND role='customer'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION["user"] = $user;
            header("Location: dashboard/customer_dashboard.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "User not found!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer Login</title>
  <style>
    body {
      background: #20232a; color: white; font-family: 'Segoe UI', sans-serif;
      display: flex; align-items: center; justify-content: center; height: 100vh;
    }
    .form-box {
      background: #2c2f36; padding: 30px; border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3); width: 100%; max-width: 400px;
    }
    input, button {
      width: 100%; padding: 12px; margin-bottom: 15px; border-radius: 8px;
      border: none; font-size: 1rem;
    }
    input {
      background: #3c3f47; color: white;
    }
    button {
      background: #ff3d00; color: white; font-weight: bold; cursor: pointer;
    }
    .error {
      color: #ff5252; text-align: center;
    }
  </style>
</head>
<body>

<div class="form-box">
  <h2 style="text-align:center;">Customer Login</h2>
  <form method="POST" action="">
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Password" required />
    <button type="submit">Login</button>
    <p class="error"><?php echo $error; ?></p>
    <p style="text-align:center;">Don't have an account? <a href="register.php" style="color: #00e5cf;">Sign Up</a></p>
  </form>
</div>

</body>
</html>
