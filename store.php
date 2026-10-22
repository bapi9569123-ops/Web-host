<?php
session_start();
if (!isset($_SESSION['role'])) exit;
$sourcesFile = 'bot_sources.json';
$sources = file_exists($sourcesFile) ? json_decode(file_get_contents($sourcesFile), true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f0f2f5; margin: 0; padding-bottom: 80px; }
        .app-bar { background: #6200ee; color: white; padding: 16px; text-align: center; font-weight: bold; }
        .store-item { background: white; margin: 15px; padding: 20px; border-radius: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-left: 6px solid #6200ee; }
        .download-btn { display: inline-block; margin-top: 15px; padding: 10px 20px; background: #6200ee; color: white; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 13px; }
        .bottom-nav { position: fixed; bottom: 0; width: 100%; height: 65px; background: white; display: flex; justify-content: space-around; align-items: center; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="app-bar">PREMIUM BOT STORE</div>

    <?php if(empty($sources)) echo "<p style='text-align:center; margin-top:50px; color:#888;'>Store is empty. Admin will add bots soon!</p>"; ?>

    <?php foreach($sources as $s): ?>
    <div class="store-item">
        <h3 style="margin:0; color:#1a1040;"><?php echo htmlspecialchars($s['name']); ?></h3>
        <p style="color:#666; font-size:14px; margin:10px 0;"><?php echo nl2br(htmlspecialchars($s['desc'])); ?></p>
        <a href="data:application/x-httpd-php;base64,<?php echo $s['code']; ?>" download="bot.php" class="download-btn">
            <i class="fas fa-download"></i> DOWNLOAD .PHP
        </a>
    </div>
    <?php endforeach; ?>

    <div class="bottom-nav">
        <a href="index.php" style="color:#666; text-decoration:none;"><i class="fas fa-home" style="display:block; font-size:20px; margin-bottom:5px;"></i>Home</a>
        <a href="store.php" style="color:#6200ee; text-decoration:none;"><i class="fas fa-code" style="display:block; font-size:20px; margin-bottom:5px;"></i>Store</a>
        <a href="logout.php" style="color:#666; text-decoration:none;"><i class="fas fa-sign-out-alt" style="display:block; font-size:20px; margin-bottom:5px;"></i>Logout</a>
    </div>
</body>
</html>
