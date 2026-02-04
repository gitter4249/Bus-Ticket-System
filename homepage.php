<?php
session_start();
// 1. 引入数据库配置
require_once "includes/config.php";

// 2. 检查登录状态
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// 3. 登录成功提示逻辑
$popup = isset($_SESSION['login_success']);
unset($_SESSION['login_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BusEase | Transport Booking</title>
    <?php include "includes/header.php"; ?>
</head>
<body>

<?php include "includes/navbar.php"; ?>

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <section class="search-section">
            <h2>Where do you want to go?</h2>

            <form class="search-form" method="GET" action="service_catalogue.php">
                <div class="form-group">
                    <label>From</label>
                    <select name="from_loc" >
                        <option value="">All</option>
                        <option>Kuala Lumpur</option>
                        <option>Penang</option>
                        <option>Johor Bahru</option>
                        <option>Melaka</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>To</label>
                    <select name="to_loc" >
                        <option value="">All</option>
                        <option>Penang</option>
                        <option>Kuala Lumpur</option>
                        <option>Ipoh</option>
                        <option>Johor Bahru</option>
                        <option>Melaka</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Departure Date</label>
                    <input type="date" name="date"  min="<?= date('Y-m-d') ?>">
                </div>

                <button type="submit" class="search-btn">Search Bus</button>
            </form>
        </section>
    </div>
</section>

<section class="steps-wrapper">
    <div class="steps-container">
        <div class="step">
            <div class="step-number">1</div>
            <h3>Choose Route</h3>
            <p>Select your departure and destination.</p>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <h3>Select Seat</h3>
            <p>Choose your preferred seat and bus.</p>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <h3>Make Payment</h3>
            <p>Pay securely and receive your e-ticket.</p>
        </div>
    </div>
</section>

<section class="faq-wrapper">
    <div class="faq-container">
        <h2 class="faq-title">Frequently Asked Questions</h2>

        <div class="faq-item">
            <input type="checkbox" id="faq1">
            <label for="faq1">How do I book a bus ticket?</label>
            <div class="faq-content">
                <p>Select your route, date, bus and seat, then proceed to payment.</p>
            </div>
        </div>

        <div class="faq-item">
            <input type="checkbox" id="faq2">
            <label for="faq2">Can I cancel or change my booking?</label>
            <div class="faq-content">
                <p>Cancellations and changes depend on the bus operator policy.</p>
            </div>
        </div>

        <div class="faq-item">
            <input type="checkbox" id="faq3">
            <label for="faq3">What payment methods are accepted?</label>
            <div class="faq-content">
                <p>FPX online banking, debit cards and selected e-wallets.</p>
            </div>
        </div>

        <div class="faq-item">
            <input type="checkbox" id="faq5">
            <label for="faq5">Is my information secure?</label>
            <div class="faq-content">
                <p>All user data is protected using secure sessions.</p>
            </div>
        </div>
    </div>
</section>

<?php if ($popup): ?>
<script>
    alert("Login Successful! Welcome back.");
</script>
<?php endif; ?>

<?php include "includes/footer.php"; ?>

</body>
</html>