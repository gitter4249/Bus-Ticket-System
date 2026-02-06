<?php
session_start();
include "includes/config.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | BusEase</title>
    
    <?php include "includes/header.php"; ?>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background: url('css/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }
        
        .member-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 35px 20px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            text-align: center;
        }
        .member-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
        .avatar-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50% !important;
            border: 5px solid #fff;
        }
        .member-name { color: #2c3e50; font-weight: 700; margin-top: 20px; }
        .section-title { color: white; text-shadow: 2px 2px 8px rgba(0,0,0,0.6); font-weight: 800; margin: 30px 0 50px 0; }
        .info-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            border-left: 5px solid #6366f1;
        }
    </style>
</head>
<body>

<?php include "includes/navbar.php"; ?>

<div class="container mt-5">
    <h1 class="text-center section-title">Meet Our Team</h1>
    
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="member-card shadow-sm">
                <img src="images/shj.png" class="avatar-img">
                <h4 class="member-name">Sim Heng Jing</h4>
                <p class="text-primary fw-bold">242DT241L8</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="member-card shadow-sm">
                <img src="images/shp.png" class="avatar-img">
                <h4 class="member-name">Sim Heng Ping</h4>
                <p class="text-primary fw-bold">242DT241L9</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="member-card shadow-sm">
                <img src="images/ngl.png" class="avatar-img">
                <h4 class="member-name">Ng Yong Lok</h4>
                <p class="text-primary fw-bold">242DT2425J</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="member-card info-card py-4 shadow-sm text-start ps-5">
                <h5 class="fw-bold mb-3">Project Information</h5>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted">Course: <br><strong>Internet and Web Publishing</strong></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted">Group Name: <br><strong>Conjuring</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>

</body>
</html>
