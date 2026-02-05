<?php
require 'includes/config.php';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($email) && !empty($password)) {
    $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('This email is already registered!');
                window.location.href='register.php';
              </script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: index.php?success=1");
        exit;
    } else {
        die("Registration failed: " . $conn->error);
    }
} else {
    header("Location: register.php");
    exit;
}
?>