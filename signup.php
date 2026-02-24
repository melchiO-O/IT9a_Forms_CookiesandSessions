<?php
// signup.php uses GET method — form data is passed via URL query string
session_start();
if (isset($_SESSION['user'])) { header("Location: dashboard.php"); exit(); }

if (!isset($_SESSION['users'])) $_SESSION['users'] = [];

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['username'])) {
    // Read values from $_GET (submitted via GET method)
    $username = trim($_GET['username'] ?? '');
    $email    = trim($_GET['email']    ?? '');
    $password =      $_GET['password'] ?? '';
    $confirm  =      $_GET['confirm']  ?? '';

    // 1. Required fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm))
        $errors[] = "All fields are required.";

    // 2. Username length (3–20)
    if (!empty($username) && (strlen($username) < 3 || strlen($username) > 20))
        $errors[] = "Username must be 3–20 characters.";

    // 3. Valid email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Enter a valid email address.";

    // 4. Password minimum length
    if (!empty($password) && strlen($password) < 6)
        $errors[] = "Password must be at least 6 characters.";

    // 5. Passwords match
    if (!empty($password) && $password !== $confirm)
        $errors[] = "Passwords do not match.";

    if (empty($errors)) {
        foreach ($_SESSION['users'] as $u) {
            if (strtolower($u['username']) === strtolower($username)) {
                $errors[] = "Username already taken."; break;
            }
        }
        if (empty($errors)) {
            $_SESSION['users'][] = [
                'username' => $username,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ];
            $success = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up — fastLANE</title>
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

        input {
            width: 100%; padding: 9px 12px; margin-bottom: 14px;
            border: 1px solid #ccc; border-radius: 5px;
            font-family: 'Fira Sans', sans-serif;
            font-size: 0.95rem; outline: none;
        }
        input:focus { border-color: #c0392b; }

        .btn {
            width: 100%; padding: 11px;
            background: #c0392b; color: #fff; border: none;
            border-radius: 5px; font-family: 'Fira Sans', sans-serif;
            font-size: 1rem; font-weight: 600; cursor: pointer;
        }
        .btn:hover { background: #a93226; }

        .error { color: #c0392b; font-size: 0.85rem; margin-bottom: 14px; }
        .error ul { padding-left: 18px; }

        .success { color: #27ae60; font-size: 0.85rem; margin-bottom: 14px; text-align: center; }

        .link { text-align: center; margin-top: 16px; font-size: 0.85rem; color: #888; }
        .link a { color: #c0392b; text-decoration: none; }
        .link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="card">
    <h1>fastLANE</h1>
    <p class="sub">Create your account</p>

    <?php if ($success): ?>
        <p class="success">Account created! <a href="login.php">Log in now</a></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="error"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>

    <!-- method="get" sends data as URL query string: signup.php?username=...&email=... -->
    <form method="get" action="signup.php">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($_GET['username'] ?? '') ?>" placeholder="Your username">

        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($_GET['email'] ?? '') ?>" placeholder="you@email.com">

        <label>Password</label>
        <input type="password" name="password" placeholder="Min. 6 characters">

        <label>Confirm Password</label>
        <input type="password" name="confirm" placeholder="Repeat password">

        <button type="submit" class="btn">Sign Up</button>
    </form>

    <div class="link">Already have an account? <a href="login.php">Log in</a></div>
</div>
</body>
</html>