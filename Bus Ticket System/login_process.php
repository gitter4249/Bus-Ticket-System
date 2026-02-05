<?php
session_start();
require 'includes/config.php'; 

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) 
{
    if (password_verify($password, $row['password'])) 
    {
        $_SESSION['user'] = $email;
        $_SESSION['login_success'] = true;
        header("Location: homepage.php");
        exit;
    } 
    else 
    {
        echo "<script>
                alert('Wrong email or password!');
                window.location.href='index.php';
              </script>";
        exit;
    }
} 
else 
{
    echo "<script>
            alert('Wrong email or password!');
            window.location.href='index.php';
          </script>";
    exit;
}

header("Location: index.php");
exit;
?>