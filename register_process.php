<?php
require 'includes/config.php';

// 获取输入
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!empty($email) && !empty($password)) {
    // 1. 检查 Email 是否已经存在
    $check = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // 如果 Email 已存在，弹窗并返回
        echo "<script>
                alert('This email is already registered!');
                window.location.href='register.php';
              </script>";
        exit;
    }

    // 2. 加密密码并插入数据
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        // 注册成功，跳回登录页
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