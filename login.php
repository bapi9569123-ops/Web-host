<?php
session_start();
$usersFile = 'users.json';
$error = "";
if (isset($_POST['login'])) {
    $u = trim($_POST['user'] ?? ''); $p = trim($_POST['pass'] ?? '');
    if ($u === "admin" && $p === "admin") {
        $_SESSION['role'] = 'admin'; $_SESSION['username'] = 'Admin';
        header("Location: admin_dashboard.php"); exit;
    } 
    if (file_exists($usersFile)) {
        $userData = json_decode(file_get_contents($usersFile), true);
        if (isset($userData[$u]) && $userData[$u]['pass'] === $p) {
            if (($userData[$u]['status'] ?? 'active') === 'blocked') { $error = "Account Blocked!"; }
            else { $_SESSION['role'] = 'user'; $_SESSION['username'] = $u; header("Location: index.php"); exit; }
        } else { $error = "Invalid Credentials!"; }
    } else { $error = "No users found. Sign Up first."; }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Bot Hosting | Crazy Host</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #fdfdfd; margin: 0; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .login-card { width: 90%; max-width: 400px; text-align: center; padding: 20px; animation: fadeIn 0.6s ease; }
        
        /* Big Title Styling */
        .big-title { 
            font-size: 32px; 
            font-weight: 900; 
            color: #1a1040; 
            margin-bottom: 5px; 
            text-transform: uppercase;
            letter-spacing: -1px;
            line-height: 1;
        }
        .sub-title { color: #6200ee; font-weight: bold; margin-bottom: 30px; display: block; font-size: 14px; }

        input { width: 100%; padding: 16px; margin-bottom: 12px; border: 1px solid #e0e0e0; border-radius: 14px; box-sizing: border-box; font-size: 16px; background: #f9f9f9; outline: none; }
        input:focus { border-color: #6200ee; background: #fff; }

        .btn-login { width: 100%; padding: 16px; background: #6200ee; color: white; border: none; border-radius: 14px; font-weight: 900; font-size: 16px; cursor: pointer; box-shadow: 0 6px 20px rgba(98,0,238,0.3); margin-top: 10px; }
        
        /* Features Section */
        .features-grid { margin-top: 30px; text-align: left; background: #f3f0ff; padding: 20px; border-radius: 20px; border: 1px dashed #6200ee; }
        .feature-item { display: flex; align-items: center; margin-bottom: 12px; color: #1a1040; font-size: 13px; font-weight: 600; }
        .feature-item i { color: #03dac6; margin-right: 12px; font-size: 16px; }

        .footer-links { margin-top: 25px; font-size: 14px; color: #666; }
        .footer-links a { color: #6200ee; text-decoration: none; font-weight: bold; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="big-title">TELEGRAM BOT<br>HOSTING</div>
        <span class="sub-title">CRAZY HOST CLOUD PLATFORM</span>

        <form method="POST">
            <input type="text" name="user" placeholder="Username" required>
            <input type="password" name="pass" placeholder="Password" required>
            <button type="submit" name="login" class="btn-login">LOGIN TO DASHBOARD</button>
        </form>

        <div class="features-grid">
            <div class="feature-item">
                <i class="fas fa-check-circle"></i>
                <span>FREE BOT HOSTING 100% 24/7 ONLINE</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-microchip"></i>
                <span>SUPPORT ALL TYPE OF BOT (PHP/WEBHOOK)</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-tachometer-alt"></i>
                <span>SPEED HIGHER THAN OTHER SITES</span>
            </div>
            <div class="feature-item" style="margin-bottom: 0;">
                <i class="fas fa-shield-alt"></i>
                <span>SECURE ISOLATED USER DIRECTORIES</span>
            </div>
        </div>

        <div class="footer-links">
            New User? <a href="signup.php">Register Now</a>
        </div>
        
        <?php if($error) echo "<p style='color:red; font-size:13px; margin-top:15px; font-weight:bold;'>$error</p>"; ?>
    </div>

</body>
</html>
