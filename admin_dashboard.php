<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: login.php"); exit; }

$usersFile = 'users.json';
$botDir = 'bots/';
$sourcesFile = 'bot_sources.json';

$users = json_decode(file_get_contents($usersFile), true);
$allSources = file_exists($sourcesFile) ? json_decode(file_get_contents($sourcesFile), true) : [];

// --- LOGIC: DELETE FROM STORE ---
if (isset($_GET['del_source'])) {
    $id = $_GET['del_source'];
    $allSources = array_filter($allSources, function($s) use ($id) { return $s['id'] != $id; });
    file_put_contents($sourcesFile, json_encode(array_values($allSources), JSON_PRETTY_PRINT));
    header("Location: admin_dashboard.php?msg=SourceRemoved"); exit;
}

// --- LOGIC: DELETE USER BOT ---
if (isset($_GET['del_user_bot'])) {
    $path = $_GET['del_user_bot']; // Expected: bots/username/bot_id
    if (is_dir($path)) {
        array_map('unlink', glob("$path/*.*"));
        rmdir($path);
    }
    header("Location: admin_dashboard.php?msg=BotDeleted"); exit;
}

// Calculate Total Bots
$totalBotsCount = 0;
$userFolders = is_dir($botDir) ? array_filter(glob($botDir . '*'), 'is_dir') : [];
foreach($userFolders as $uf) {
    $totalBotsCount += count(array_filter(glob($uf . '/*'), 'is_dir'));
}

// Handle Bot Source Upload
if (isset($_POST['upload_source'])) {
    if($_FILES['s_file']['tmp_name']){
        $allSources[] = [
            'id' => time(),
            'name' => $_POST['s_name'],
            'desc' => $_POST['s_desc'],
            'code' => base64_encode(file_get_contents($_FILES['s_file']['tmp_name']))
        ];
        file_put_contents($sourcesFile, json_encode($allSources, JSON_PRETTY_PRINT));
        $statusMsg = "Source Published!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f4f7f6; margin: 0; padding-bottom: 50px; }
        .app-bar { background: #1a1040; color: white; padding: 20px; text-align: center; font-weight: bold; }
        .container { padding: 15px; }
        .card { background: white; padding: 18px; border-radius: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .stat-box { background: #6200ee; color: white; padding: 20px; border-radius: 20px; text-align: center; margin-bottom: 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #6200ee; margin: 15px 0 10px; text-transform: uppercase; }
        input, textarea { width: 100%; padding: 12px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box; }
        .btn { background: #6200ee; color: white; border: none; padding: 14px; width: 100%; border-radius: 12px; font-weight: bold; cursor: pointer; }
        .list-item { background: white; padding: 15px; border-radius: 15px; margin-bottom: 8px; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #ddd; }
        .act-btn { text-decoration: none; font-size: 12px; font-weight: bold; padding: 6px 10px; border-radius: 8px; margin-left: 5px; }
    </style>
</head>
<body>

<div class="app-bar">SYSTEM ADMINISTRATION</div>

<div class="container">
    <div class="stat-box">
        <p style="margin:0; opacity:0.8;">Total Active Bots Globally</p>
        <h2 style="margin:5px 0; font-size:32px;"><?php echo $totalBotsCount; ?></h2>
    </div>

    <div class="section-title">Upload to Store</div>
    <div class="card">
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="s_name" placeholder="Source Name" required>
            <textarea name="s_desc" placeholder="Source Description" required></textarea>
            <input type="file" name="s_file" required>
            <button type="submit" name="upload_source" class="btn" style="background:#00c853;">PUBLISH TO STORE</button>
        </form>
    </div>

    <div class="section-title">Store Management</div>
    <?php foreach($allSources as $s): ?>
    <div class="list-item" style="border-left-color: #03dac6;">
        <span><b><?php echo $s['name']; ?></b></span>
        <a href="?del_source=<?php echo $s['id']; ?>" class="act-btn" style="background:#ffebee; color:red;" onclick="return confirm('Remove from store?')">DELETE</a>
    </div>
    <?php endforeach; ?>

    <div class="section-title">Live User Bots</div>
    <?php 
    foreach($userFolders as $uf): 
        $owner = basename($uf);
        $bots = array_filter(glob($uf . '/*'), 'is_dir');
        foreach($bots as $b):
            $botPath = $b . "/bot.php";
    ?>
    <div class="list-item">
        <div style="font-size:13px;"><b><?php echo basename($b); ?></b><br><small>Owner: <?php echo $owner; ?></small></div>
        <div>
            <a href="webhook.php?file=<?php echo urlencode($botPath); ?>" class="act-btn" style="background:#f3e5f5; color:#6200ee;">EDIT</a>
            <a href="?del_user_bot=<?php echo urlencode($b); ?>" class="act-btn" style="background:#ffebee; color:red;" onclick="return confirm('Delete this user bot?')">DEL</a>
        </div>
    </div>
    <?php endforeach; endforeach; ?>

    <div class="section-title">Broadcast Notice</div>
    <div class="card">
        <form method="POST">
            <input type="text" name="title" placeholder="Notice Title" required>
            <textarea name="msg" placeholder="Message..." required></textarea>
            <button type="submit" name="send_notice" class="btn">PUSH NOTIFICATION</button>
        </form>
    </div>
</div>
</body>
</html>
