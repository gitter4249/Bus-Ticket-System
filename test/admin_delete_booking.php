<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

require 'includes/config.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $sql = "DELETE FROM bookings WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        
        echo "<script>
                alert('Booking deleted successfully!');
                window.location.href='admin_dashboard.php';
              </script>";
        exit;
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>