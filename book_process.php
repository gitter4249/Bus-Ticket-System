<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
    $email = $_SESSION['user'];
    $from = $_POST['from_loc'];
    $to = $_POST['to_loc'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $seat = $_POST['seat_number'];

    // --- 二次检查：防止并发重复订座 ---
    $check = $conn->prepare("SELECT id FROM bookings WHERE from_loc=? AND to_loc=? AND travel_date=? AND travel_time=? AND seat_number=?");
    $check->bind_param("sssss", $from, $to, $date, $time, $seat);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        echo "<script>alert('Sorry, this seat was just taken! Please choose another.'); window.location='homepage.php';</script>";
        exit;
    }

    // 正式插入数据库
    $stmt = $conn->prepare("INSERT INTO bookings (user_email, from_loc, to_loc, travel_date, travel_time, seat_number) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $email, $from, $to, $date, $time, $seat);

    if ($stmt->execute()) {
        echo "<script>alert('Payment Successful! Booking Confirmed.'); window.location='homepage.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>