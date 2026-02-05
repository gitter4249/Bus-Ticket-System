<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: admin_login.php"); exit; }
include "includes/config.php"; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_bus'])) {
    $company = mysqli_real_escape_string($conn, $_POST['bus_company']);
    $dep = mysqli_real_escape_string($conn, $_POST['departure']);
    $dest = mysqli_real_escape_string($conn, $_POST['destination']);
    $date = $_POST['travel_date'];
    $time = $_POST['depart_time'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO buses (bus_company, departure, destination, travel_date, depart_time, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $company, $dep, $dest, $date, $time, $price);
    
    if($stmt->execute()) {
        echo "<script>alert('Bus added successfully!'); window.location='admin_dashboard.php?view=buses';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
    exit;
}

if (isset($_GET['delete_bus'])) {
    $bus_id = $_GET['delete_bus'];
    $stmt = $conn->prepare("DELETE FROM buses WHERE id = ?");
    $stmt->bind_param("i", $bus_id);
    $stmt->execute();
    header("Location: admin_dashboard.php?view=buses");
    exit;
}

$view = $_GET['view'] ?? 'bookings'; 
$sel_route = $_GET['route'] ?? null;
$sel_date  = $_GET['date'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard | BusEase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { display: flex; min-height: 100vh; background: #f4f7f6; font-family: 'Segoe UI', sans-serif; margin: 0; }
        .sidebar { width: 260px; background: #2c3e50; color: white; position: fixed; height: 100vh; padding: 20px; display: flex; flex-direction: column; }
        .sidebar-logo { text-align: center; margin-bottom: 30px; padding: 10px; }
        .sidebar-logo img { max-width: 180px; height: auto; }
        .nav-menu { flex: 1; }
        .nav-link-custom { display: flex; align-items: center; color: #bdc3c7; text-decoration: none; padding: 12px 15px; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #34495e; color: #3498db; }
        .nav-link-custom i { margin-right: 12px; font-size: 1.2rem; }
        .main-content { flex: 1; margin-left: 260px; padding: 40px; width: calc(100% - 260px); }
        .card-custom { background: white; border: none; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); padding: 30px; }
        .btn-date { background: #3498db; color: white; border: none; padding: 10px 20px; border-radius: 8px; margin: 5px; text-decoration: none; display: inline-block; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-logo">
        <img src="images/logo3.png" alt="BusEase Logo">
    </div>
    <div class="nav-menu">
        <a href="admin_dashboard.php?view=bookings" class="nav-link-custom <?= $view == 'bookings' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Booking Management
        </a>
        <a href="admin_dashboard.php?view=buses" class="nav-link-custom <?= $view == 'buses' ? 'active' : '' ?>">
            <i class="bi bi-bus-front"></i> Manage Buses
        </a>
        <a href="homepage.php" class="nav-link-custom">
            <i class="bi bi-house-door"></i> View Website
        </a>
        <hr style="border-color: #7f8c8d;">
        <a href="logout.php" class="nav-link-custom text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <div class="card-custom">
        
        <?php if($view == 'buses'): ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Manage Bus Schedules</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBusModal">
                    <i class="bi bi-plus-lg"></i> Add New Bus
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Company</th>
                            <th>Departure</th>
                            <th>Destination</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Price</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $res = $conn->query("SELECT * FROM buses ORDER BY travel_date DESC, depart_time ASC");
                        while($b = $res->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= htmlspecialchars($b['bus_company'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($b['departure']) ?></td>
                                <td><?= htmlspecialchars($b['destination']) ?></td>
                                <td><?= $b['travel_date'] ?></td>
                                <td><?= $b['depart_time'] ?></td>
                                <td>RM <?= number_format($b['price'], 2) ?></td>
                                <td class="text-end">
                                    <a href="?view=buses&delete_bus=<?= $b['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this schedule?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>
            <?php if(!$sel_route): ?>
                <h4 class="mb-4 fw-bold">Select Route</h4>
                <div class="list-group list-group-flush">
                    <?php 
                    $res = $conn->query("SELECT DISTINCT departure, destination FROM buses");
                    while($r = $res->fetch_assoc()): 
                        $name = $r['departure']." -> ".$r['destination']; ?>
                        <a href="?route=<?= urlencode($name) ?>" class="list-group-item list-group-item-action route-item">
                            <span class="fw-bold"><?= $name ?></span>
                            <i class="bi bi-chevron-right float-end"></i>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php elseif(!$sel_date): ?>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold">Select Date (<?= htmlspecialchars($sel_route) ?>)</h4>
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary btn-sm">Back</a>
                </div>
                <div class="d-flex flex-wrap">
                    <?php 
                    list($f, $t) = explode(" -> ", $sel_route);
                    $stmt = $conn->prepare("SELECT DISTINCT travel_date FROM buses WHERE departure=? AND destination=?");
                    $stmt->bind_param("ss", $f, $t); $stmt->execute(); $res = $stmt->get_result();
                    while($r = $res->fetch_assoc()): ?>
                        <a href="?route=<?=urlencode($sel_route)?>&date=<?=$r['travel_date']?>" class="btn btn-date">
                            <i class="bi bi-calendar-check me-1"></i> <?=$r['travel_date']?>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <div><h4 class="fw-bold text-primary mb-1"><?= htmlspecialchars($sel_route) ?></h4><p class="text-muted m-0">Passenger list for <?=$sel_date?></p></div>
                    <a href="?route=<?=urlencode($sel_route)?>" class="btn btn-sm btn-secondary">Change Date</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Seat No.</th>
                                <th>Passenger Details</th>
                                <th>Account Email</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            list($f, $t) = explode(" -> ", $sel_route);
                            $sql = "SELECT bk.id AS bid, b.depart_time, bk.seat_no, bk.user_email, bk.p_name, bk.p_phone, bk.p_email 
                                    FROM bookings bk 
                                    JOIN buses b ON bk.bus_id = b.id 
                                    WHERE b.departure=? AND b.destination=? AND b.travel_date=? 
                                    ORDER BY b.depart_time ASC";
                            $stmt = $conn->prepare($sql); $stmt->bind_param("sss", $f, $t, $sel_date); $stmt->execute(); $res = $stmt->get_result();
                            while($r = $res->fetch_assoc()): ?>
                                <tr>
                                    <td><?=$r['depart_time']?></td>
                                    <td><span class="badge bg-info-subtle text-info-emphasis px-3">Seat #<?=$r['seat_no']?></span></td>
                                    <td>
                                        <div class="fw-bold"><?=htmlspecialchars($r['p_name'] ?? '')?></div>
                                        <small class="text-muted"><?=htmlspecialchars($r['p_phone'] ?? '')?></small><br>
                                        <small class="text-muted"><?=htmlspecialchars($r['p_email'] ?? '')?></small>
                                    </td>
                                    <td><?=htmlspecialchars($r['user_email'])?></td>
                                    <td><span class="text-success fw-bold">● Paid</span></td>
                                    <td class="text-end"><a href="admin_delete_booking.php?id=<?=$r['bid']?>" class="text-danger fw-bold text-decoration-none" onclick="return confirm('Delete?')">Delete</a></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="addBusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Add New Bus Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Bus Company</label>
                    <input type="text" name="bus_company" class="form-control" placeholder="e.g. Mayang Sari" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Departure</label>
                    <input type="text" name="departure" class="form-control" placeholder="e.g. Kuala Lumpur" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Destination</label>
                    <input type="text" name="destination" class="form-control" placeholder="e.g. Penang" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Travel Date</label>
                    <input type="date" name="travel_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Depart Time</label>
                    <input type="time" name="depart_time" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price (RM)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="add_bus" class="btn btn-primary px-4">Save Bus</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>