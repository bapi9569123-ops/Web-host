<?php
session_start();

define("ADMIN_USER", "admin");
define("ADMIN_PASS", "123");

$dataFile = "data.json";
$bots = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

/* ---------- LOGIN ---------- */
if (isset($_POST['login'])) {
    if ($_POST['user'] === ADMIN_USER && $_POST['pass'] === ADMIN_PASS) {
        $_SESSION['admin'] = true;
        header("Location: panel.php");
        exit;
    }
    $error = "❌ Invalid login";
}

/* ---------- LOGOUT ---------- */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: panel.php");
    exit;
}

/* ---------- AUTH CHECK ---------- */
if (!isset($_SESSION['admin'])) {
?>
<form method="post">
<h3>🔐 Admin Login</h3>
<input name="user" placeholder="Username" required><br>
<input name="pass" type="password" placeholder="Password" required><br>
<button name="login">Login</button>
<?= $error ?? "" ?>
</form>
<?php exit; }

/* ---------- DASHBOARD ---------- */
$total = count($bots);
$live = count(array_filter($bots, fn($b)=>$b['status']=="live"));
$dead = $total - $live;
?>

<h2>🤖 Bot Hosting Panel</h2>
<p>Total Bots: <?= $total ?></p>
<p>Live Bots: <?= $live ?></p>
<p>Dead Bots: <?= $dead ?></p>

<a href="?logout">Logout</a>

<hr>

<h3>➕ Add Bot</h3>
<form method="post" action="api.php">
<input name="name" placeholder="Bot Name" required>
<input name="owner" placeholder="Owner" required>
<input name="token" placeholder="Bot Token" required>
<button name="add">Add Bot</button>
</form>

<hr>

<h3>📋 Bot List</h3>
<table border="1">
<tr><th>Name</th><th>Owner</th><th>Status</th><th>Action</th></tr>

<?php foreach ($bots as $b): ?>
<tr>
<td><?= $b['name'] ?></td>
<td><?= $b['owner'] ?></td>
<td><?= $b['status'] ?></td>
<td>
<form method="post" action="api.php">
<input type="hidden" name="id" value="<?= $b['id'] ?>">
<select name="action">
<option>select</option>
<option value="start">Start</option>
<option value="stop">Stop</option>
<option value="restart">Restart</option>
<option value="delete">Delete</option>
</select>
<button>Go</button>
</form>
</td>
</tr>
<?php endforeach; ?>
</table>