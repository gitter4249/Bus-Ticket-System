<?php
session_start();

// 1. 安全检查：必须是管理员登录
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'includes/config.php';

// 2. 获取 ID
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 3. 执行删除语句
    $sql = "DELETE FROM bookings WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        // 删除成功，弹窗提示并返回
        echo "<script>
                alert('Booking deleted successfully!');
                window.location.href='admin_dashboard.php';
              </script>";
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // 如果没有 ID，直接退回 dashboard
    header("Location: admin_dashboard.php");
    exit;
}
?>