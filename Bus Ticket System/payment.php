<?php
include "includes/config.php"; 
session_start();

if (!isset($_POST['bus_id']) || !isset($_POST['seats'])) {
    header("Location: homepage.php");
    exit;
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$bus_id = mysqli_real_escape_string($conn, $_POST['bus_id']);
$seats  = mysqli_real_escape_string($conn, $_POST['seats']);

$p_name  = $_POST['p_name'] ?? '';
$p_email = $_POST['p_email'] ?? '';
$p_phone = $_POST['p_phone'] ?? '';

$sql = "SELECT * FROM buses WHERE id = '$bus_id'";
$result = mysqli_query($conn, $sql);
$bus = mysqli_fetch_assoc($result);

$seatArray = explode(',', $seats);
$seatCount = count(array_filter($seatArray));
$total = $bus['price'] * $seatCount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Payment | BusEase</title>
    <link rel="stylesheet" href="css/style payment.css">
    <style>
        body { 
            font-family: 'Segoe UI', 
            Arial, sans-serif; 
            background-color: #f8fafc; 
            margin: 0; 
        }

        .navbar{
            background: #fff;
            border-bottom: 1px solid #eee; 
            padding: 15px 0; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        .navbar-content { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            width: 90%; 
            margin: auto; 
        }

        .logo img { 
            width: 180px; 
            height: auto; 
        } 

        .nav-right { 
            display: flex; 
            align-items: center; 
            gap: 20px; 
            font-weight: bold; 
        }

        .user-email { 
            color: #4f46e5; 
        }

        .nav-link { 
            text-decoration: none;
            color: #4f46e5; 
            border-left: 1px solid #ddd; 
            padding-left: 15px; 
        }

        .logout-btn { 
            color: #dc3545; 
            text-decoration: none; 
            }
    </style>
</head>

<?php include "includes/navbar.php"; ?>

<div class="page-container">
    <div class="card left">
        <h2 style="margin-bottom:25px; color: #1e293b;">Select Payment Method</h2>
        <form action="payment_success.php" method="POST" id="paymentForm">
            
            <div class="option active" onclick="openOption('fpx', this, 'FPX')">
                <span>FPX Online Banking</span> <span>▼</span>
            </div>
            <div id="fpx" class="sub-option" style="display:block;">
                <label><input type="radio" name="payment_detail" value="Maybank" required checked> Maybank2u</label>
                <label><input type="radio" name="payment_detail" value="CIMB"> CIMB Clicks</label>
                <label><input type="radio" name="payment_detail" value="Public Bank"> Public Bank</label>
                <label><input type="radio" name="payment_detail" value="RHB"> RHB Now</label>
            </div>

            <div class="option" onclick="openOption('card', this, 'Card')">
                <span>Credit/ Debit Card</span> <span>▼</span>
            </div>
            <div id="card" class="sub-option">
                <label><input type="radio" name="payment_detail" value="Visa"> Visa</label>
                <label><input type="radio" name="payment_detail" value="Mastercard"> Mastercard</label>
            </div>

            <div class="option" onclick="openOption('wallet', this, 'E-Wallet')">
                <span>E-Wallet</span> <span>▼</span>
            </div>
            <div id="wallet" class="sub-option">
                <label><input type="radio" name="payment_detail" value="Touch n Go"> Touch 'n Go eWallet</label>
                <label><input type="radio" name="payment_detail" value="GrabPay"> GrabPay</label>
                <label><input type="radio" name="payment_detail" value="Boost"> Boost</label>
            </div>

            <input type="hidden" name="payment_method" id="payment_method" value="FPX">
            <input type="hidden" name="bus_id" value="<?= $bus_id ?>">
            <input type="hidden" name="seats" value="<?= $seats ?>">
            <input type="hidden" name="total_price" value="<?= $total ?>">
            
            <input type="hidden" name="p_name" value="<?= htmlspecialchars($p_name) ?>">
            <input type="hidden" name="p_email" value="<?= htmlspecialchars($p_email) ?>">
            <input type="hidden" name="p_phone" value="<?= htmlspecialchars($p_phone) ?>">
        </form>
    </div>

    <div class="card right">
        <h2 style="margin-bottom:20px; color: #1e293b;">Order Summary</h2>
        <div class="row"><span>Route</span><strong><?= htmlspecialchars($bus['departure']) ?> → <?= htmlspecialchars($bus['destination']) ?></strong></div>
        <div class="row"><span>Date</span><strong><?= $bus['travel_date'] ?></strong></div>
        <div class="row"><span>Seats Selected</span><strong><?= $seats ?></strong></div>
        <div class="row"><span>Passenger</span><strong><?= htmlspecialchars($p_name) ?></strong></div>
        
        <div class="row total-row">
            <span>Total Amount</span>
            <span style="color: #4f46e5;">RM <?= number_format($total, 2) ?></span>
        </div>
        
        <button type="button" class="btn-confirm" onclick="submitPayment()">Confirm & Pay Now</button>
    </div>
</div>

<?php include "includes/footer.php"; ?>

<script>
function openOption(type, element, method){
    document.querySelectorAll('.sub-option').forEach(s => s.style.display='none');
    document.querySelectorAll('.option').forEach(o => o.classList.remove('active'));
    document.getElementById(type).style.display = 'block';
    element.classList.add('active');
    document.getElementById('payment_method').value = method;
}

function submitPayment(){
    document.getElementById('paymentForm').submit();
}
</script>
</body>
</html>
