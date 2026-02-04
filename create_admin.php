<?php
require 'includes/db.php';

// 你想要设置的账号和密码
$user = 'hengping';
$pass = 'hengping123';

// 关键点：对密码进行哈希加密
$hashed_password = password_hash($pass, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $user, $hashed_password);

if ($stmt->execute()) {
    echo "<h3>Admin account created successfully!</h3>";
    echo "Username: " . $user . "<br>";
    echo "Password: " . $pass . "<br>";
    echo "<a href='admin_login.php'>Go to Admin Login</a>";
} else {
    echo "Error: " . $conn->error;
}
?>