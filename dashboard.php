<?php
session_start();

if (!isset($_SESSION['logged_in'])) {
    header("Location: sign_log.php");
    exit();
}

// LOGOUT
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    setcookie("remember_name", "", time() - 3600, "/");
    header("Location: sign_log.php");
    exit();
}

$name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class ="container">
<h2>Dashboard</h2>

<?php if (isset($_COOKIE['remember_name'])): ?>
    <p>Welcome back, <?= $name ?>!</p>
<?php else: ?>
    <p>Welcome, <?= $name ?>!</p>
<?php endif; ?>

<a href="dashboard.php?logout=true">Logout</a>
</div>
</body>
</html>