<?php
// login.php uses POST method — form data is sent in the request body (not visible in URL)
session_start();
if (isset($_SESSION['user'])) { header("Location: dashboard.php"); exit(); }

if (!isset($_SESSION['users'])) $_SESSION['users'] = [];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read values from $_POST (submitted via POST method)
    $username = trim($_POST['username'] ?? '');
    $password =      $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    // 1. Required fields
    if (empty($username)) $errors[] = "Username is required.";
    if (empty($password)) $errors[] = "Password is required.";

    if (empty($errors)) {
        $found = false;
        foreach ($_SESSION['users'] as $u) {
            if (strtolower($u['username']) === strtolower($username)) {
                if (password_verify($password, $u['password'])) {
                    $found = true;
                    $_SESSION['user'] = ['username' => $u['username'], 'email' => $u['email']];
                    setcookie('fastlane_user', $remember ? $u['username'] : '', $remember ? time() + 604800 : time() - 3600, '/');
                    header("Location: dashboard.php");
                    exit();
                }
                break;
            }
        }
        if (!$found) $errors[] = "Invalid username or password.";
    }
}

$remembered = $_COOKIE['fastlane_user'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In — fastLANE</title>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Fira Sans', sans-serif;
            background: #f4f4f4;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .card {
            background: #fff;
            padding: 36px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 380px;
        }

        h1 { text-align: center; font-size: 1.8rem; font-weight: 700; color: #c0392b; margin-bottom: 4px; }
        .sub { text-align: center; color: #888; font-size: 0.85rem; margin-bottom: 4px; }

        label { display: block; font-size: 0.85rem; color: #555; margin-bottom: 4px; }

        input[type="text"],
        input[type="password"] {
            width: 100%; padding: 9px 12px; margin-bottom: 14px;
            border: 1px solid #ccc; border-radius: 5px;
            font-family: 'Fira Sans', sans-serif;
            font-size: 0.95rem; outline: none;
        }
        input:focus { border-color: #c0392b; }

        .remember {
            display: flex; align-items: center; gap: 6px;
            font-size: 0.85rem; color: #888; margin-bottom: 16px;
        }

        .btn {
            width: 100%; padding: 11px;
            background: #c0392b; color: #fff; border: none;
            border-radius: 5px; font-family: 'Fira Sans', sans-serif;
            font-size: 1rem; font-weight: 600; cursor: pointer;
        }
        .btn:hover { background: #a93226; }

        .error { color: #c0392b; font-size: 0.85rem; margin-bottom: 14px; }
        .error ul { padding-left: 18px; }

        .link { text-align: center; margin-top: 16px; font-size: 0.85rem; color: #888; }
        .link a { color: #c0392b; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <h1>fastLANE</h1>
    <p class="sub">Sign in to your account</p>

    <?php if (!empty($errors)): ?>
        <div class="error"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <!-- method="post" sends data in the request body — password is not visible in the URL -->
    <form method="post" action="login.php">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($_POST['username'] ?? $remembered) ?>" placeholder="Your username">

        <label>Password</label>
        <input type="password" name="password" placeholder="Your password">

        <label class="remember">
            <input type="checkbox" name="remember" <?= $remembered ? 'checked' : '' ?>>
            Remember me
        </label>

        <button type="submit" class="btn">Log In</button>
    </form>

    <div class="link">No account yet? <a href="signup.php">Sign up</a></div>
</div>
</body>
</html> 