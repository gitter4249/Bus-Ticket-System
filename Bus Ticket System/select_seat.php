<?php
include "includes/config.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div style='text-align:center;padding:50px;'><h2>Error: No Bus ID provided!</h2><a href='service_catalogue.php'>Back</a></div>");
}

$bus_id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "SELECT * FROM buses WHERE id='$bus_id'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("<div style='text-align:center;padding:50px;'><h2>Error: Bus not found in database!</h2><a href='service_catalogue.php'>Back</a></div>");
}

$bus = mysqli_fetch_assoc($result);

$name  = $_SESSION['user_name']  ?? '';
$email = $_SESSION['user_email'] ?? '';
$phone = $_SESSION['user_phone'] ?? '';

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
    <link rel="stylesheet" href="css/style seat.css">
</head>

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
                                <label>FULL NAME</label>
                                <input type="text" name="p_name" required value="<?= htmlspecialchars($name) ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label>EMAIL</label>
                                <input type="email" name="p_email" required value="<?= htmlspecialchars($email) ?>">
                            </div>
                            <div class="form-group">
                                <label>PHONE NUMBER</label>
                                <input type="text" name="p_phone" required value="<?= htmlspecialchars($phone) ?>">
                            </div>
                        </div>
                    </div>

                    <a href="service_catalogue.php" class="btn-back">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="right">
                    <h2 style="margin-bottom: 5px;">Select Your Seat</h2>
                    <p class="limit-info">Choose your seats below</p>
                    
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
const rows = 5;
const bookedSeats = <?= $bookedSeatsJson ?>;
const selectedSeats = [];
const pricePerSeat = <?= $bus['price'] ?>;

const seatMap = document.getElementById('seatMap');
const seatInput = document.getElementById('seatInput');
const totalPriceInput = document.getElementById('totalPriceInput');
const seatText = document.getElementById('selectedSeats');

for (let r = 1; r <= rows; r++) {
    for (let c = 1; c <= 5; c++) { 
        if (c === 3) {
            const aisle = document.createElement('div');
            aisle.className = 'aisle';
            seatMap.appendChild(aisle);
        } else {
            const charIndex = c > 3 ? c - 1 : c;
            createSeat(r, charIndex);
        }
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
