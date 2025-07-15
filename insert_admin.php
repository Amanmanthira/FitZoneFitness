<?php
// Change these details as needed
$name = "Admin";
$email = "admin@gmail.com";
$password = "admin123";  // For production, hash the password!
$role = "admin";

// Simple password hashing (recommended)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Connect to DB
$conn = new mysqli("localhost", "root", "", "fitzone");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Admin with this email already exists.";
} else {
    $stmt->close();
    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
    if ($stmt->execute()) {
        echo "Admin user inserted successfully.";
    } else {
        echo "Error inserting admin user: " . $stmt->error;
    }
}
$stmt->close();
$conn->close();
?>
