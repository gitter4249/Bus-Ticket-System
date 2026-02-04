<?php
session_start();
if(isset($_SESSION['user'])){
    header("Location: homepage.php");
    exit;
}
$success = $_GET['success'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusEase - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            /* 使用你上传的那张背景图 */
            background: url('css/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            margin: 0;
        }

        /* 增加一个深色遮罩层，让背景图不刺眼，表单更清晰 */
        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.2); /* 20% 透明度的黑色遮罩 */
            z-index: -1;
        }

        .login-card {
            border: none;
            border-radius: 25px;
            /* 毛玻璃效果 */
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 15px;
        }

        .logo-section { 
            text-align: center; 
            padding: 20px 10px 10px 10px; 
        }

        /* 你的要求：Logo 宽度 250px */
        .logo-section img { 
            width: 250px; 
            height: auto;
            margin-bottom: 5px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: scale(1.02);
        }

        .form-control { 
            border-radius: 10px; 
            padding: 12px;
            background: rgba(255, 255, 255, 0.8);
        }

        .admin-link { color: #dc3545; text-decoration: none; font-weight: 600; font-size: 0.85rem;}
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card login-card">
                <div class="logo-section">
                    <img src="images/logo3.png" alt="BusEase Logo">
                    <h4 class="fw-bold text-primary">Sign In</h4>
                </div>
                
                <div class="card-body p-4 pt-2">
                    <?php if ($success): ?>
                        <div class="alert alert-success py-2 text-center small">Success! Please login.</div>
                    <?php endif; ?>

                    <form action="login_process.php" method="post">
                        <div class="mb-3">
                            <label class="small fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="small text-muted">New here? <a href="register.php" class="text-primary fw-bold text-decoration-none">Register</a></p>
                        <hr>
                        <a href="admin_login.php" class="admin-link">Staff Access Only</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>