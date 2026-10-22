<?php
$usersFile = 'users.json';
$error = ""; $success = "";

if (isset($_POST['register'])) {
    $u = preg_replace('/[^A-Za-z0-9]/', '', $_POST['user']); 
    $p = trim($_POST['pass'] ?? '');
    
    if (strlen($u) < 4) { $error = "Username kam se kam 4 characters ka hona chahiye!"; }
    else {
        $currentData = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
        if (isset($currentData[$u])) { $error = "Ye Username pehle se koi le chuka hai!"; }
        else {
            $currentData[$u] = ["pass" => $p, "status" => "active", "joined" => date("d M Y")];
            file_put_contents($usersFile, json_encode($currentData, JSON_PRETTY_PRINT));
            $success = "Account Created! Ab Login karein...";
            header("refresh:2;url=login.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Free Account | Telegram Bot Hosting Cloud</title>
    
    <meta name="description" content="Join Crazy Host: The #1 Free Telegram Bot Hosting for PHP developers. 24/7 Uptime, High Speed, and Secure Bot Deployment.">
    <meta name="keywords" content="Telegram Bot Hosting Free, Best Bot Host 2026, PHP Webhook Host, Fast Telegram Hosting, Crazy Host Signup">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f4f7f6; margin: 0; padding: 20px 0; display: flex; flex-direction: column; align-items: center; min-height: 100vh; }
        .signup-card { background: white; width: 90%; max-width: 420px; padding: 30px; border-radius: 28px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); text-align: center; }
        
        .badge-new { background: #03dac6; color: #1a1040; padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: bold; margin-bottom: 10px; display: inline-block; }
        h2 { color: #1a1040; margin: 0 0 10px; font-size: 28px; font-weight: 900; }
        
        .input-group { text-align: left; margin-bottom: 15px; }
        .input-group label { font-size: 12px; font-weight: bold; color: #6200ee; margin-left: 5px; }
        input { width: 100%; padding: 15px; margin-top: 5px; border: 1px solid #eee; border-radius: 14px; box-sizing: border-box; background: #fdfdfd; font-size: 16px; }
        
        .btn-reg { width: 100%; padding: 16px; background: #6200ee; color: white; border: none; border-radius: 14px; font-weight: bold; cursor: pointer; font-size: 17px; box-shadow: 0 6px 15px rgba(98,0,238,0.3); margin-top: 10px; }

        /* Trust Badges */
        .trust-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 25px; }
        .trust-item { background: #f9f9f9; padding: 12px; border-radius: 15px; font-size: 11px; font-weight: bold; color: #555; display: flex; align-items: center; gap: 8px; border: 1px solid #efefef; }
        .trust-item i { color: #6200ee; font-size: 14px; }

        .login-link { margin-top: 25px; display: block; color: #6200ee; text-decoration: none; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="signup-card">
        <div class="badge-new">FASTEST CLOUD 2026</div>
        <h2>GET STARTED</h2>
        <p style="color:#777; font-size:13px; margin-bottom:25px;">Create your account and host your first bot in 60 seconds.</p>
        
        <form method="POST">
            <div class="input-group">
                <label>USERNAME (Alphanumeric)</label>
                <input type="text" name="user" placeholder="e.g. CrazyUser7" required>
            </div>
            <div class="input-group">
                <label>SECURE PASSWORD</label>
                <input type="password" name="pass" placeholder="••••••••" required>
            </div>
            <button type="submit" name="register" class="btn-reg">CREATE FREE ACCOUNT</button>
        </form>

        <div class="trust-grid">
            <div class="trust-item"><i class="fas fa-bolt"></i> INSTANT SETUP</div>
            <div class="trust-item"><i class="fas fa-lock"></i> SSL SECURE</div>
            <div class="trust-item"><i class="fas fa-server"></i> 99.9% UPTIME</div>
            <div class="trust-item"><i class="fas fa-code"></i> PHP SUPPORT</div>
        </div>

        <div style="margin-top: 20px; font-size: 10px; color: #aaa; line-height: 1.4;">
            By signing up, you agree to our 24/7 Policy. Support for all Telegram Bot types.
            Crazy Host is the fastest growing Telegram Bot Host.
        </div>
        
        <a href="login.php" class="login-link">Already have an account? Log In</a>
        
        <?php if($error) echo "<p style='color:#d32f2f; font-size:13px; margin-top:15px; font-weight:bold;'>$error</p>"; ?>
        <?php if($success) echo "<p style='color:#2e7d32; font-size:13px; margin-top:15px; font-weight:bold;'>$success</p>"; ?>
    </div>

</body>
</html>
