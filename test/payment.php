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
    <?php include "includes/header.php"; ?>
    <style>
        .page-container { max-width: 1100px; margin: 40px auto; display: flex; gap: 30px; padding: 0 20px; min-height: 65vh; }
        .card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        .left { flex: 2; } 
        .right { flex: 1.2; height: fit-content; position: sticky; top: 20px; }
        
        .option { padding: 18px; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 12px; cursor: pointer; font-weight: bold; display: flex; justify-content: space-between; transition: 0.2s; }
        .option.active { background: #eff6ff; border-color: #4f46e5; color: #4f46e5; }
        .sub-option { display: none; padding: 20px; background: #f8fafc; border-left: 4px solid #4f46e5; margin-bottom: 15px; border-radius: 0 8px 8px 0; }
        .sub-option label { display: block; margin-bottom: 12px; cursor: pointer; font-size: 0.95rem; }
        .sub-option input[type="radio"] { margin-right: 10px; transform: scale(1.2); }

        .row { display: flex; justify-content: space-between; margin: 15px 0; color: #64748b; }
        .row strong { color: #1e293b; }
        .total-row { font-size: 22px; font-weight: 800; color: #1e293b; border-top: 1px solid #f1f5f9; padding-top: 20px; margin-top: 20px; }
        .btn-confirm { width: 100%; padding: 18px; background: #4f46e5; color: white; border: none; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        .btn-confirm:hover { background: #4338ca; transform: translateY(-2px); }
    </style>
</head>
<body style="background:#f9fafb;">

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
        </form>
    </div>

    <div class="card right">
        <h2 style="margin-bottom:20px; color: #1e293b;">Order Summary</h2>
        <div class="row"><span>Route</span><strong><?= htmlspecialchars($bus['departure']) ?> → <?= htmlspecialchars($bus['destination']) ?></strong></div>
        <div class="row"><span>Date</span><strong><?= $bus['travel_date'] ?></strong></div>
        <div class="row"><span>Departure Time</span><strong><?= $bus['depart_time'] ?></strong></div>
        <div class="row"><span>Seats Selected</span><strong><?= $seats ?></strong></div>
        <div class="row"><span>Total Tickets</span><strong><?= $seatCount ?> Ticket(s)</strong></div>
        
        <div class="row total-row">
            <span>Total Amount</span>
            <span style="color: #4f46e5;">RM <?= number_format($total, 2) ?></span>
        </div>
        
        <p style="font-size: 0.8rem; color: #94a3b8; text-align: center; margin-top: 15px;">        </p>
        
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