<?php
session_start();
if (!isset($_SESSION['role'])) { exit("Unauthorized"); }

$user = $_SESSION['username'];
$usersFile = 'users.json';
$userDirBase = "bots/" . $user . "/";

function blockUser($username, $reason) {
    global $usersFile;
    $users = json_decode(file_get_contents($usersFile), true);
    if (isset($users[$username])) {
        $users[$username]['status'] = 'blocked';
        $users[$username]['block_reason'] = $reason;
        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));
    }
    session_destroy();
    header("Location: login.php?error=Security Violation: Account Terminated.");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['botfile'])) {
    $tmpPath = $_FILES['botfile']['tmp_name'];
    $botContent = file_get_contents($tmpPath);

    // --- 1. ANTI-SHELL & ANTI-HACK SCAN ---
    $malicious = ['shell_exec', 'system', 'passthru', 'eval', 'itwasdie', '$_GET[\'cmd\']', '$_POST[\'cmd\']'];
    foreach ($malicious as $bad) {
        if (stripos($botContent, $bad) !== false) {
            blockUser($user, "Hacking attempt detected.");
        }
    }

    // --- 2. ANTI-BOT-HOSTING-BOT SCAN (New!) ---
    // Ye check karta hai ki kya user koi file manager ya hosting bot chala raha hai
    $hosting_patterns = ['mkdir(', 'rmdir(', 'unlink(', 'file_put_contents(', 'ZipArchive', '$_FILES'];
    $matchCount = 0;
    foreach ($hosting_patterns as $pattern) {
        if (stripos($botContent, $pattern) !== false) {
            $matchCount++;
        }
    }
    
    // Agar ek hi file mein file creation aur deletion ke 3+ patterns mile, toh wo hosting bot hai
    if ($matchCount >= 3) {
        blockUser($user, "Hosting-type bots are not allowed on this server.");
    }

    // --- 3. TOKEN ANALYSIS (MUST HAVE) ---
    if (!preg_match('/(\d+:[\w-]{35,})/', $botContent, $matches)) {
        blockUser($user, "No valid Telegram Bot Token found.");
    }

    // --- 4. DEPLOYMENT ---
    $botID = "bot_" . uniqid();
    $userDir = $userDirBase . $botID;
    if (!is_dir($userDir)) mkdir($userDir, 0777, true);
    $destination = $userDir . "/bot.php";

    if (move_uploaded_file($tmpPath, $destination)) {
        $token = $matches[1];
        $protocol = (!empty($_SERVER['HTTPS']) ? "https" : "http");
        $webhookUrl = "$protocol://{$_SERVER['HTTP_HOST']}/$destination";
        @file_get_contents("https://api.telegram.org/bot$token/setWebhook?url=" . urlencode($webhookUrl));
        header("Location: index.php?msg=Bot Deployed!");
        exit;
    }
}
?>
