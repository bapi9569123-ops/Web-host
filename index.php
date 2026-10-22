<?php
session_start();
if (!isset($_SESSION['role'])) { header("Location: login.php"); exit; }
$user = $_SESSION['username'];
$userDir = "bots/" . $user . "/";

if (isset($_GET['delete'])) {
    $folder = $_GET['delete'];
    if (strpos($folder, "bots/$user/") === 0 && is_dir($folder)) {
        array_map('unlink', glob("$folder/*.*")); rmdir($folder);
        header("Location: index.php"); exit;
    }
}
$myBots = is_dir($userDir) ? array_filter(glob($userDir . '*'), 'is_dir') : [];
$notice = file_exists('notice.json') ? json_decode(file_get_contents('notice.json'), true) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Dashboard | Crazy Host</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f0f2f5; margin: 0; padding-bottom: 90px; }
        .app-bar { background: #6200ee; color: white; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; position: sticky; top:0; z-index:99; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .welcome-card { background: white; padding: 25px 20px; margin-bottom: 10px; border-radius: 0 0 25px 25px; }
        .source-btn { background: linear-gradient(135deg, #00c6ff, #0072ff); color: white; padding: 18px; border-radius: 16px; margin: 10px; display: flex; align-items: center; justify-content: space-between; text-decoration: none; box-shadow: 0 4px 12px rgba(0,114,255,0.2); }
        .bot-card { background: white; margin: 10px; padding: 16px; border-radius: 18px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        
        /* Bottom Navigation */
        .bottom-nav { position: fixed; bottom: 0; width: 100%; height: 70px; background: white; display: flex; justify-content: space-around; align-items: center; border-top: 1px solid #eee; z-index: 1000; }
        .nav-item { text-align: center; text-decoration: none; color: #757575; flex: 1; transition: 0.3s; }
        .nav-item i { font-size: 20px; display: block; margin-bottom: 4px; }
        .nav-item.active { color: #6200ee; font-weight: bold; }
        
        /* Store Icon Special Style */
        .store-icon { background: #f3e5f5; color: #6200ee; width: 45px; height: 45px; border-radius: 15px; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 22px; }

        .action-btns a { width: 40px; height: 40px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; margin-left: 5px; }
        .fab { position: fixed; bottom: 90px; right: 20px; background: #6200ee; color: white; width: 56px; height: 56px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 24px; text-decoration: none; box-shadow: 0 4px 15px rgba(98,0,238,0.4); z-index: 999; }
        
        /* Notice Modal */
        #noticeOverlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px); }
        .notice-modal { background: white; width: 85%; max-width: 400px; border-radius: 28px; padding: 25px; text-align: center; position: relative; animation: slideUp 0.4s ease; }
        @keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>

    <div class="app-bar">
        <h1 style="font-size: 20px; margin:0;">Crazy Host</h1>
        <i class="fas fa-user-circle" style="font-size: 24px;"></i>
    </div>

    <div class="welcome-card">
        <h2 style="margin:0; font-size: 22px;">Namaste, <?php echo htmlspecialchars($user); ?></h2>
        <p style="color:#666; font-size:14px; margin:8px 0 0;">
            <i class="fas fa-server" style="color:#03dac6;"></i> &nbsp;Bots Active: <?php echo count($myBots); ?>/10
        </p>
    </div>

    <a href="https://t.me/telebothosting" class="source-btn">
        <span><i class="fab fa-telegram-plane"></i> &nbsp;<b>Free Bot Source</b></span>
        <i class="fas fa-external-link-alt" style="font-size: 14px;"></i>
    </a>

    <div style="padding:15px 18px 5px; font-weight:bold; color:#888; font-size:12px; letter-spacing: 1px;">MANAGEMENT</div>
    
    <?php if(empty($myBots)) echo "<div style='text-align:center; padding:40px; color:#aaa;'><i class='fas fa-folder-open' style='font-size:40px; display:block; margin-bottom:10px;'></i>No bots found. Tap + to upload!</div>"; ?>

    <?php foreach($myBots as $b): $p = $b."/bot.php"; ?>
    <div class="bot-card">
        <div style="display:flex; align-items:center;">
            <div style="background:#f3e5f5; color:#6200ee; width:45px; height:45px; border-radius:14px; display:flex; align-items:center; justify-content:center; margin-right:15px; font-size:18px;"><i class="fas fa-robot"></i></div>
            <b><?php echo basename($b); ?></b>
        </div>
        <div class="action-btns">
            <a href="webhook.php?file=<?php echo urlencode($p); ?>" style="background:#f3e5f5; color:#6200ee;"><i class="fas fa-pen"></i></a>
            <a href="?delete=<?php echo urlencode($b); ?>" onclick="return confirm('Pakka delete karun?')" style="background:#ffebee; color:#d32f2f;"><i class="fas fa-trash"></i></a>
        </div>
    </div>
    <?php endforeach; ?>

    <?php if(count($myBots) < 10): ?>
        <a href="upload_form.php" class="fab"><i class="fas fa-plus"></i></a>
    <?php endif; ?>

    <div class="bottom-nav">
        <a href="index.php" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="store.php" class="nav-item">
            <div class="store-icon"><i class="fas fa-code"></i></div>
            <span>Store</span>
        </a>
        <a href="logout.php" class="nav-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Exit</span>
        </a>
    </div>

    <?php if($notice): ?>
    <div id="noticeOverlay">
        <div class="notice-modal">
            <div onclick="closeN()" style="position:absolute; top:18px; right:18px; color:#ccc; cursor:pointer;"><i class="fas fa-times-circle fa-lg"></i></div>
            <div style="background:#6200ee; width:55px; height:55px; border-radius:18px; display:flex; align-items:center; justify-content:center; margin:-50px auto 18px; color:white; font-size:22px; box-shadow:0 8px 15px rgba(98,0,238,0.2);"><i class="fas fa-bell"></i></div>
            <h3 style="margin:0; color:#1a1040;"><?php echo htmlspecialchars($notice['title']); ?></h3>
            <p style="color:#555; font-size:14px; line-height:1.6; margin:18px 0;"><?php echo nl2br(htmlspecialchars($notice['msg'])); ?></p>
            <button onclick="closeN()" style="width:100%; padding:14px; background:#6200ee; color:white; border:none; border-radius:14px; font-weight:bold; cursor:pointer; box-shadow:0 4px 10px rgba(98,0,238,0.2);">UNDERSTOOD</button>
            <div style="margin-top:12px; font-size:10px; color:#bbb;"><?php echo $notice['date']; ?></div>
        </div>
    </div>
    <script>
        window.onload = function() {
            if(localStorage.getItem('notice_id') !== '<?php echo $notice['id']; ?>') {
                document.getElementById('noticeOverlay').style.display = 'flex';
            }
        };
        function closeN() {
            localStorage.setItem('notice_id', '<?php echo $notice['id']; ?>');
            document.getElementById('noticeOverlay').style.display = 'none';
        }
    </script>
    <?php endif; ?>
</body>
</html>
