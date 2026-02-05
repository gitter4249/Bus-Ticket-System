<?php
require_once "includes/config.php"; 
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$where = [];
$where[] = "status = 1";

if (isset($_GET['from_loc']) && $_GET['from_loc'] != "" && $_GET['from_loc'] != "All") {
    $from = mysqli_real_escape_string($conn, $_GET['from_loc']);
    $where[] = "departure LIKE '%$from%'";
}

if (isset($_GET['to_loc']) && $_GET['to_loc'] != "" && $_GET['to_loc'] != "All") {
    $to = mysqli_real_escape_string($conn, $_GET['to_loc']);
    $where[] = "destination LIKE '%$to%'";
}

if (isset($_GET['date']) && $_GET['date'] != "" && $_GET['date'] != "Any Date") {
    $date = mysqli_real_escape_string($conn, $_GET['date']);
    $where[] = "travel_date = '$date'";
}

$sql = "SELECT * FROM buses";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Query Failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Available Buses | BusEase</title>
    <?php include "includes/header.php"; ?>
    <style>
        .content-layout {
            display: flex;
            gap: 30px;
            margin-top: 30px;
            min-height: 60vh;
        }

        .filter-panel {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            height: fit-content;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .main-content {
            flex: 3;
        }

        .bus-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .bus-card:hover {
            transform: translateY(-5px);
        }

        .bus-info h3 {
            color: #4f46e5;
            margin: 0 0 10px 0;
            font-size: 1.4rem;
        }

        .bus-info p {
            margin: 5px 0;
            color: #555;
        }

        .price-section {
            text-align: right;
        }

        .price-section h2 {
            color: #ff4757;
            font-size: 1.8rem;
            margin: 5px 0 10px 0;
        }

        .view-btn {
            background: #4f46e5;
            color: #fff;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }

        .no-results {
            text-align: center;
            padding: 50px;
            background: #fff;
            border-radius: 12px;
        }
    </style>
</head>

<?php include "includes/navbar.php"; ?>

<div class="container">
    <div class="content-layout">

        <aside class="filter-panel">
            <h4 style="margin-top:0;">Search Summary</h4>
            <hr style="border:0; border-top:1px solid #eee; margin:15px 0;">
            <p style="font-size:0.9rem;"><b>From:</b> <?= (isset($_GET['from_loc']) && $_GET['from_loc'] !== '') ? htmlspecialchars($_GET['from_loc']) : 'All' ?></p>
            <p style="font-size:0.9rem;"><b>To:</b> <?= (isset($_GET['to_loc']) && $_GET['to_loc'] !== '') ? htmlspecialchars($_GET['to_loc']) : 'All' ?></p>
            <p style="font-size:0.9rem;"><b>Date:</b> <?= (isset($_GET['date']) && $_GET['date'] !== '') ? htmlspecialchars($_GET['date']) : 'Any Date' ?></p>
            <br>
            <a href="homepage.php" style="color:#4f46e5; text-decoration:none; font-size:0.85rem; font-weight:bold;">← Change Search</a>
        </aside>

        <main class="main-content">
            <h2 style="margin-bottom: 20px; color: #1e293b;">Available Bus Services</h2>

            <section class="bus-list">
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <div class="bus-card">
                            <div class="bus-info">
                                <h3><?= htmlspecialchars($row['bus_company'] ?? 'Express Bus') ?></h3>
                                <p><strong>Route:</strong> <?= htmlspecialchars($row['departure']) ?> ➔ <?= htmlspecialchars($row['destination']) ?></p>
                                <p><strong>Date:</strong> <?= $row['travel_date'] ?></p>
                                <p><strong>Time:</strong> <?= $row['depart_time'] ?></p>
                            </div>
                            <div class="price-section">
                                <p style="color:#888; margin:0; font-size:0.9rem;">Price per seat</p>
                                <h2>RM <?= number_format($row['price'], 2) ?></h2>
                                <a href="select_seat.php?id=<?= $row['id'] ?>" class="view-btn">Select Seats</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-results">
                        <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" width="80" style="opacity:0.3;" alt="No result">
                        <h3 style="margin-top:20px; color:#999;">No Buses Found</h3>
                        <p>Sorry, we couldn't find any buses for your selected route or date.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<?php include "includes/footer.php"; ?>

</body>
</html>
