<?php
include "includes/config.php"; 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bus_id  = mysqli_real_escape_string($conn, $_POST['bus_id']);
    $seats   = mysqli_real_escape_string($conn, $_POST['seats']);
    
    $p_name  = mysqli_real_escape_string($conn, $_POST['p_name'] ?? '');
    $p_email = mysqli_real_escape_string($conn, $_POST['p_email'] ?? '');
    $p_phone = mysqli_real_escape_string($conn, $_POST['p_phone'] ?? '');
    $total   = mysqli_real_escape_string($conn, $_POST['total_price'] ?? '0');
   
    $user_email = $_SESSION['user'] ?? ($_SESSION['email'] ?? 'Guest'); 

    $sql = "INSERT INTO bookings (bus_id, seat_no, user_email, total_price, p_name, p_email, p_phone, booking_date) 
            VALUES ('$bus_id', '$seats', '$user_email', '$total', '$p_name', '$p_email', '$p_phone', NOW())";

    if (!mysqli_query($conn, $sql)) {
        die("Database Error: " . mysqli_error($conn));
    }
} else {
    header("Location: homepage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=homepage.php">
    <title>Payment Success</title>
    <style>
        body { font-family: 'Segoe UI', Arial; background: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 20px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-width: 400px; }
        .icon { font-size: 60px; color: #2ecc71; margin-bottom: 20px; }
        h2 { color: #1c1e21; margin: 0; }
        p { color: #606770; margin-top: 10px; }
        .btn { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #1877f2; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">✔</div>
        <h2>Payment Successful!</h2>
        <p>Seat(s) <strong><?= htmlspecialchars($seats) ?></strong> reserved for <?= htmlspecialchars($user_email) ?>.</p>
        <a href="homepage.php" class="btn">Back to Home Now</a>
        <p><small>Automatically redirecting in 3 seconds...</small></p>
    </div>
</body>
</html>