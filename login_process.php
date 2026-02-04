<?php
session_start();
require 'includes/config.php'; 

// 获取表单数据
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 1. 准备并执行查询
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// 2. 逻辑判断
if ($row = $result->fetch_assoc()) 
{
    // 如果用户存在，验证加密密码
    if (password_verify($password, $row['password'])) 
    {
        // 登录成功
        $_SESSION['user'] = $email;
        $_SESSION['login_success'] = true;
        header("Location: homepage.php");
        exit;
    } 
    else 
    {
        // 密码错误
        echo "<script>
                alert('Wrong email or password!');
                window.location.href='index.php';
              </script>";
        exit;
    }
} 
else 
{
    // 邮箱不存在
    echo "<script>
            alert('Wrong email or password!');
            window.location.href='index.php';
          </script>";
    exit;
}

// 防火墙：防止任何意外情况绕过逻辑
header("Location: index.php");
exit;
?>