<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BusEase - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('css/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex; align-items: center; margin: 0;
        }
        body::before {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.2); z-index: -1;
        }
        .register-card {
            border: none; border-radius: 25px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 15px;
        }
        .logo-section { text-align: center; padding: 20px 10px 10px 10px; }
        .logo-section img { width: 250px; height: auto; }
        .btn-success {
            background-color: #198754; border: none; padding: 12px;
            border-radius: 12px; font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card register-card">
                <div class="logo-section">
                    <img src="images/logo3.png" alt="BusEase Logo">
                    <h4 class="fw-bold text-success">Join Us</h4>
                </div>
                <div class="card-body p-4 pt-2">
                    <form action="register_process.php" method="post">
                        <div class="mb-3">
                            <label class="small fw-bold">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email" required style="border-radius:10px; padding:12px;">
                        </div>
                        <div class="mb-4">
                            <label class="small fw-bold">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password" required style="border-radius:10px; padding:12px;">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Create Account</button>
                    </form>
                    <div class="text-center mt-3">
                        <p class="small text-muted">Member already? <a href="index.php" class="text-success fw-bold text-decoration-none">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>