<?php
session_start();

// Initialize users storage
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [];
}

$errors = [];
$message = "";
$mode = isset($_GET['mode']) ? $_GET['mode'] : "login";

// ================== SIGNUP ==================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    // VALIDATIONS
    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    }

    if (strlen($name) < 3) {
        $errors[] = "Name must be at least 3 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $_SESSION['users'][$email] = [
            "name" => $name,
            "password" => $password
        ];

        header("Location: sign_log.php?registered=success");
        exit();
    }
}

// ================== LOGIN ==================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]);

    if (isset($_SESSION['users'][$email]) &&
        $_SESSION['users'][$email]['password'] === $password) {

        $_SESSION['logged_in'] = true;
        $_SESSION['user_name'] = $_SESSION['users'][$email]['name'];

        if ($remember) {
            setcookie("remember_name", $_SESSION['user_name'], time() + 86400, "/");
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}

// GET success message
if (isset($_GET['registered'])) {
    $message = "Registration successful! Please login.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authentication</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
<h2><?= $mode == "signup" ? "Signup" : "Login" ?></h2>

<?php
foreach ($errors as $error) {
    echo "<p class='error'>$error</p>";
}
?>

<p><?= $message ?></p>

<?php if ($mode == "signup"): ?>

<!-- SIGNUP FORM -->
<form method="POST">
    <input type="text" name="name" placeholder="Name"><br>
    <input type="text" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="password" name="confirm" placeholder="Confirm Password"><br>
    <button type="submit" name="signup">Register</button>
</form>

<p>Already have an account?
    <a href="sign_log.php?mode=login">Login</a>
</p>

<?php else: ?>

<!-- LOGIN FORM -->
<form method="POST">
    <input type="text" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Password"><br>
    <input type="checkbox" name="remember"><span>Remember Me</span><br>
    <button type="submit" name="login">Login</button>
</form>

<p>No account?
    <a href="sign_log.php?mode=signup">Signup</a>
</p>

<?php endif; ?>
</div>
</body>
</html>