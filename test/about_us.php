<?php
session_start();
include "includes/config.php"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | Our Team</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('css/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar { background: white; padding: 10px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        .member-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 35px 20px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
            border: none;
            text-align: center;
            height: 100%; 
        }
        .member-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.2); }
        
        .avatar-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border-radius: 50% !important;
        }
        
        .member-name { color: #2c3e50; font-weight: 700; margin-top: 20px; font-size: 1.25rem; }
        .member-id { color: #6366f1; font-size: 0.95rem; font-weight: 600; letter-spacing: 0.5px; }
        .section-title { color: white; text-shadow: 2px 2px 8px rgba(0,0,0,0.6); font-weight: 800; margin-bottom: 50px; margin-top: 30px; }
        
        .info-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            border-left: 5px solid #6366f1;
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="logo">
            <a href="homepage.php"><img src="images/logo3.png" alt="BusEase Logo" height="40"></a>
        </div>
        <div class="nav-right">
            <a href="homepage.php" class="text-decoration-none text-dark fw-bold"><i class="bi bi-arrow-left"></i> Back to Home</a>
        </div>
    </div>
</header>

<div class="container mt-5">
    <h1 class="text-center section-title">Meet Our Team</h1>
    
    <div class="row g-4 justify-content-center">
        <div class="col-md-4">
            <div class="member-card shadow-sm">
                <img src="images/shj.png" class="avatar-img" alt="Sim Heng Jing">
                <h4 class="member-name">Sim Heng Jing</h4>
                <p class="member-id">Student ID: 242DT241L8</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="member-card shadow-sm">
                <img src="images/shp.png" class="avatar-img" alt="Sim Heng Ping">
                <h4 class="member-name">Sim Heng Ping</h4>
                <p class="member-id">Student ID: 242DT241L9</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="member-card shadow-sm">
                <img src="images/ngl.png" class="avatar-img" alt="Ng Yong Lok">
                <h4 class="member-name">Ng Yong Lok</h4>
                <p class="member-id">Student ID: 242DT2425J</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="member-card info-card py-4 shadow-sm text-start ps-5">
                <h5 class="fw-bold mb-3" style="color: #2c3e50;">Project Information</h5>
                <div class="row">
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted"><strong>Course:</strong> <br>Internet and Web Publishing</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="mb-1 text-muted"><strong>Group Name:</strong> <br>Conjuring</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-5">
    <?php include "includes/footer.php"; ?>
</div>

</body>
</html>