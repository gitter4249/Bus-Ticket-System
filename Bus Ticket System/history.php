<?php
session_start();
require_once "includes/config.php"; 

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user_email = $_SESSION['user'];

// Generate booking history from database data 
$sql = "SELECT b.id as booking_id, b.seat_no, b.p_name, b.p_email, b.p_phone, b.total_price, 
               bs.id as bus_id, bs.bus_company, bs.departure, bs.destination, bs.depart_time, bs.price, bs.status as bus_status
        FROM bookings b
        JOIN buses bs ON b.bus_id = bs.id
        WHERE b.user_email = ?
        ORDER BY b.id DESC"; 

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Bookings | BusEase</title>
    <?php include "includes/header.php"; ?> 
    <style>
        .history-container { max-width: 1000px; margin: 40px auto; padding: 0 20px; min-height: 450px; }
        .back-link { text-decoration: none; color: #4f46e5; font-size: 14px; margin-bottom: 20px; display: inline-block; font-weight: bold; }
        
        .history-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4f46e5; color: white; padding: 18px; text-align: left; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; }
        td { padding: 18px; border-bottom: 1px solid #f1f5f9; color: #334155; }
        
        .price-text { color: #4f46e5; font-weight: 800; font-size: 1.1rem; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .status-completed { color: #15803d; background: #dcfce7; }
        .status-refunded { color: #b91c1c; background: #fee2e2; }
        
        .seat-badge { background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-weight: bold; color: #475569; }
        .passenger-info { font-size: 13px; color: #64748b; margin-top: 4px; display: block; }
        .company-name { color: #4f46e5; font-weight: bold; font-size: 0.85rem; text-transform: uppercase; display: block; margin-bottom: 2px; }
        
        /* if cancel */
        .cancelled-row { background-color: #fafafa; opacity: 0.8; }
        .cancelled-row .price-text { color: #94a3b8; text-decoration: line-through; }
    </style>
</head>
<body style="background: #f8fafc;">
    <?php include "includes/navbar.php"; ?>

    <div class="history-container">
        <a href="homepage.php" class="back-link">← Back to Home</a>
        <h2 style="margin-bottom: 25px; color: #1e293b;">Your Booking History</h2>
        
        <div class="history-card">
            <table>
                <thead>
                    <tr>
                        <th>Route & Passenger</th> 
                        <th>Time</th>
                        <th>Seats</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): 
                            // if bus_status is 0, it means cancelled
                            $is_cancelled = ($row['bus_status'] == 0);
                            
                            $display_total = ($row['total_price'] > 0) ? $row['total_price'] : 0;
                            if($display_total == 0) {
                                $seats_arr = explode(',', $row['seat_no']);
                                $count = count(array_filter($seats_arr)); 
                                $display_total = $row['price'] * $count;
                            }
                        ?>
                        <tr class="<?= $is_cancelled ? 'cancelled-row' : '' ?>">
                            <td>
                                <span class="company-name"><?= htmlspecialchars($row['bus_company'] ?: 'Bus Express') ?></span>
                                <strong><?= htmlspecialchars($row['departure']) ?> → <?= htmlspecialchars($row['destination']) ?></strong>
                                <span class="passenger-info">Passenger: <?= htmlspecialchars($row['p_name'] ?: 'N/A') ?> </span>
                            </td>
                            <td><?= htmlspecialchars($row['depart_time']) ?></td>
                            <td><span class="seat-badge"><?= htmlspecialchars($row['seat_no']) ?></span></td>
                            <td class="price-text">
                                RM <?= number_format($display_total, 2) ?>
                                <?php if($is_cancelled): ?>
                                    <div style="font-size: 10px; color: #ef4444; font-weight: normal;">Refunded</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($is_cancelled): ?>
                                    <span class="status-badge status-refunded">Refunded</span>
                                <?php else: ?>
                                    <span class="status-badge status-completed">Confirmed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 50px; color: #94a3b8;">No booking history found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
</body>
</html>
