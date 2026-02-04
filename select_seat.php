<?php
include "includes/config.php";
session_start();

// 1. 登录检查
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// 2. 获取并检查 Bus ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div style='text-align:center;padding:50px;'><h2>❌ Error: No Bus ID provided!</h2><a href='service_catalogue.php'>Back</a></div>");
}

$bus_id = mysqli_real_escape_string($conn, $_GET['id']);

// 3. 获取巴士详情
$sql = "SELECT * FROM buses WHERE id='$bus_id'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("<div style='text-align:center;padding:50px;'><h2>❌ Error: Bus not found in database!</h2><a href='service_catalogue.php'>Back</a></div>");
}

$bus = mysqli_fetch_assoc($result);

// 4. 获取用户信息 (用于预填表单)
$name  = $_SESSION['user_name']  ?? '';
$email = $_SESSION['user_email'] ?? '';
$phone = $_SESSION['user_phone'] ?? '';

// 5. 获取已订座位
$bookedSeats = [];
$seatQuery = "SELECT seat_no FROM bookings WHERE bus_id='$bus_id'";
$seatResult = mysqli_query($conn, $seatQuery);

if ($seatResult) {
    while($row = mysqli_fetch_assoc($seatResult)){
        $seats = explode(',', $row['seat_no']);
        $bookedSeats = array_merge($bookedSeats, $seats);
    }
}
$bookedSeatsJson = json_encode($bookedSeats);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Select Seat | BusEase</title>
    <?php include "includes/header.php"; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .page-container { padding: 40px 0; min-height: 70vh; }
        .booking-layout { display: flex; gap: 40px; background: #fff; padding: 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        .left { flex: 1; border-right: 1px solid #eee; padding-right: 30px; display: flex; flex-direction: column; justify-content: space-between; }
        .right { flex: 1.2; padding-left: 10px; }
        
        /* 选座特有样式 */
        .seat-map { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 20px 0; max-width: 300px; }
        .seat { height: 40px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; cursor: pointer; border-radius: 5px; font-weight: bold; font-size: 0.9rem; transition: 0.2s; }
        .seat.booked { background: #eee; color: #ccc; cursor: not-allowed; border: none; }
        .seat.selected { background: #4f46e5; color: white; border-color: #4f46e5; }
        .seat:hover:not(.booked) { border-color: #4f46e5; color: #4f46e5; }
        
        .legend-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
        .seat.demo { width: 25px; height: 25px; cursor: default; }
        
        button[type="submit"] { 
            width: 100%; background: #4f46e5; color: white; border: none; padding: 15px; 
            border-radius: 10px; font-weight: bold; cursor: pointer; margin-top: 20px; font-size: 1.1rem; transition: 0.3s;
        }
        button[type="submit"]:hover { background: #4338ca; }

        .limit-info { color: #ef4444; font-size: 0.85rem; margin-bottom: 10px; font-weight: bold; }
        .form-group label { display:block; margin-bottom:5px; font-weight: bold; color: #444; }
        .form-group input { width:100%; padding:10px; border-radius:5px; border:1px solid #ddd; box-sizing: border-box; }
        
        /* Back Button 样式 */
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none;
            font-weight: 600; padding: 10px 20px; border: 1px solid #e2e8f0; border-radius: 8px;
            transition: all 0.3s; margin-top: 20px; width: fit-content;
        }
        .btn-back:hover { background: #f1f5f9; color: #1e293b; border-color: #cbd5e1; }
    </style>
</head>
<body style="background: #f8fafc;">

<?php include "includes/navbar.php"; ?>

<div class="container">
    <form action="payment.php" method="POST" onsubmit="return validateSeat()">
        <div class="page-container">
            <div class="booking-layout">

                <div class="left">
                    <div>
                        <div class="section">
                            <h2 style="color: #4f46e5; margin-bottom: 15px;">Bus Details</h2>
                            <p><strong>Company:</strong> <?= htmlspecialchars($bus['bus_company']) ?></p>
                            <p><strong>Route:</strong> <?= htmlspecialchars($bus['departure']) ?> → <?= htmlspecialchars($bus['destination']) ?></p>
                            <p><strong>Date:</strong> <?= $bus['travel_date'] ?></p>
                            <p><strong>Time:</strong> <?= $bus['depart_time'] ?></p>
                            <p style="color: #ff4757; font-size: 1.2rem;"><strong>Price:</strong> RM <?= number_format($bus['price'], 2) ?></p>
                        </div>

                        <hr style="margin: 25px 0; border: 0; border-top: 1px solid #f1f1f1;">

                        <div class="section">
                            <h2 style="margin-bottom: 15px;">Passenger Details</h2>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>Full Name</label>
                                <input type="text" name="name" required value="<?= htmlspecialchars($name) ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>Email</label>
                                <input type="email" name="email" required value="<?= htmlspecialchars($email) ?>">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" required value="<?= htmlspecialchars($phone) ?>">
                            </div>
                        </div>
                    </div>

                    <a href="service_catalogue.php" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="right">
                    <h2 style="margin-bottom: 5px;">Select Your Seat</h2>
                    <p class="limit-info">Max 2 seats per booking</p>
                    
                    <div style="background:#f8fafc; padding:20px; border-radius:10px; text-align:center; border: 1px solid #edf2f7;">
                        <small style="color:#94a3b8; display:block; margin-bottom:15px; letter-spacing: 2px;">FRONT / DRIVER</small>
                        <div class="seat-map" id="seatMap" style="margin: 0 auto;"></div>
                    </div>

                    <p style="margin-top:20px;"><strong>Selected Seats:</strong> 
                        <span id="selectedSeats" style="color:#4f46e5; font-weight:bold;">None</span>
                    </p>

                    <div class="seat-legend" style="margin-top:25px; padding-top: 15px; border-top: 1px solid #eee;">
                        <h3 style="font-size:1rem; margin-bottom:10px;">Legend</h3>
                        <div class="legend-row">
                            <div class="seat demo"></div><span>Available</span>
                        </div>
                        <div class="legend-row">
                            <div class="seat demo booked"></div><span>Already booked</span>
                        </div>
                        <div class="legend-row">
                            <div class="seat demo selected"></div><span>Selected by you</span>
                        </div>
                    </div>

                    <input type="hidden" name="bus_id" value="<?= $bus_id ?>">
                    <input type="hidden" name="seats" id="seatInput">
                    <input type="hidden" name="total_price" id="totalPriceInput">

                    <button type="submit">Proceed to Payment</button>
                </div>

            </div>
        </div>
    </form>
</div>

<?php include "includes/footer.php"; ?>

<script>
// ... (保持你原来的 JavaScript 逻辑不变)
const rows = 5;
const bookedSeats = <?= $bookedSeatsJson ?>;
const selectedSeats = [];
const pricePerSeat = <?= $bus['price'] ?>;
const maxSeats = 2; 

const seatMap = document.getElementById('seatMap');
const seatInput = document.getElementById('seatInput');
const totalPriceInput = document.getElementById('totalPriceInput');
const seatText = document.getElementById('selectedSeats');

for (let r = 1; r <= rows; r++) {
    for (let c = 1; c <= 4; c++) {
        createSeat(r, c);
    }
}

function createSeat(r, c) {
    const seat = document.createElement('div');
    const seatName = r + String.fromCharCode(64 + c); 

    seat.className = 'seat';
    seat.textContent = seatName;

    if (bookedSeats.includes(seatName)) {
        seat.classList.add('booked');
    } else {
        seat.onclick = () => {
            if (!selectedSeats.includes(seatName) && selectedSeats.length >= maxSeats) {
                alert("Maximum 2 seats allowed per booking!");
                return;
            }

            seat.classList.toggle('selected');
            if (selectedSeats.includes(seatName)) {
                selectedSeats.splice(selectedSeats.indexOf(seatName), 1);
            } else {
                selectedSeats.push(seatName);
            }
            
            seatText.textContent = selectedSeats.length ? selectedSeats.join(', ') : 'None';
            seatInput.value = selectedSeats.join(',');
            totalPriceInput.value = (selectedSeats.length * pricePerSeat).toFixed(2);
        };
    }
    seatMap.appendChild(seat);
}

function validateSeat() {
    if (!seatInput.value) {
        alert("Please select at least one seat.");
        return false;
    }
    return true;
}
</script>

</body>
</html>