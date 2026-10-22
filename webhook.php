<?php
session_start();
if (!isset($_SESSION['role'])) { exit("Unauthorized"); }
$user = $_SESSION['username'];
$editFile = $_GET['file'] ?? '';

if ($_SESSION['role'] !== 'admin' && strpos($editFile, "bots/$user/") === false) {
    die("Access Denied");
}

if (isset($_POST['save_code'])) {
    file_put_contents($editFile, $_POST['code']);
    $msg = "Saved Successfully!";
}
$code = file_exists($editFile) ? htmlspecialchars(file_get_contents($editFile)) : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bot Source</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f0f2f5; margin: 0; }
        .app-bar { background: #6200ee; color: white; padding: 16px; display: flex; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .app-bar h1 { font-size: 18px; margin: 0; flex: 1; margin-left: 15px; }
        .editor-container { padding: 15px; }
        textarea { 
            width: 100%; height: 70vh; background: #2d2d2d; color: #f8f8f2; 
            border: none; border-radius: 8px; padding: 15px; box-sizing: border-box;
            font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.5;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);
        }
        .fab-save { 
            position: fixed; bottom: 25px; right: 25px; background: #03dac6; 
            color: #000; width: 56px; height: 56px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 24px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); border: none;
        }
    </style>
</head>
<body>
    <div class="app-bar">
        <a href="index.php" style="color:white;"><i class="fas fa-arrow-left"></i></a>
        <h1>Editing: <?php echo basename(dirname($editFile)); ?></h1>
    </div>
    <div class="editor-container">
        <?php if(isset($msg)) echo "<p style='color:green; font-weight:bold;'>$msg</p>"; ?>
        <form method="POST">
            <textarea name="code" spellcheck="false"><?php echo $code; ?></textarea>
            <button type="submit" name="save_code" class="fab-save">
                <i class="fas fa-save"></i>
            </button>
        </form>
    </div>
</body>
</html>
