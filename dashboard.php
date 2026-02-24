<?php
// dashboard.php — displays session data and all active cookies
session_start();

if (isset($_POST['logout'])) {
    // Clear all cookies on logout
    setcookie('fastlane_remember',          '', time() - 3600, '/');
    setcookie('fastlane_last_login',        '', time() - 3600, '/');
    setcookie('fastlane_last_registered',   '', time() - 3600, '/');
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$name  = htmlspecialchars($_SESSION['user']['username']);
$email = htmlspecialchars($_SESSION['user']['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — fastLANE</title>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Fira Sans', sans-serif; background: #f4f4f4; min-height: 100vh; }

        nav {
            background: #c0392b; padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
        }
        nav .brand { color: #fff; font-size: 1.3rem; font-weight: 700; }
        nav form { margin: 0; }
        nav button {
            background: transparent; border: 1px solid #fff; color: #fff;
            padding: 6px 16px; border-radius: 4px; cursor: pointer;
            font-family: 'Fira Sans', sans-serif; font-size: 0.9rem;
        }
        nav button:hover { background: rgba(255,255,255,0.15); }

        .content {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            gap: 20px; padding: 40px 20px;
        }

        .card {
            background: #fff; padding: 36px 40px; border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1); text-align: center;
            max-width: 420px; width: 100%;
        }
        .card h2 { font-size: 1.8rem; font-weight: 700; color: #c0392b; margin-bottom: 10px; }
        .card p  { color: #555; font-size: 1rem; line-height: 1.6; }
        .name { font-weight: 600; color: #222; }

        .info-box {
            background: #fff; border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            max-width: 420px; width: 100%; overflow: hidden;
        }
        .info-box h3 {
            font-size: 0.85rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1px; padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-box h3.session-title { color: #2980b9; background: #eaf4fb; }
        .info-box h3.cookie-title  { color: #e67e22; background: #fef6ec; }

        .info-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 20px; border-bottom: 1px solid #f9f9f9;
            font-size: 0.88rem;
        }
        .info-row:last-child { border-bottom: none; }
        .info-row .key   { color: #888; }
        .info-row .value { font-weight: 600; color: #222; }

        .no-cookie { padding: 12px 20px; font-size: 0.85rem; color: #aaa; }
    </style>
</head>
<body>

<nav>
    <div class="brand">fastLANE</div>
    <form method="POST">
        <button type="submit" name="logout">Logout</button>
    </form>
</nav>

<div class="content">

    <!-- Welcome card -->
    <div class="card">
        <h2>Welcome, <span class="name"><?= $name ?></span>! 🚗</h2>
        <p>You're now logged in to <strong>fastLANE</strong> — your go-to car rental service.</p>
    </div>

    <!-- Session data -->
    <div class="info-box">
        <h3 class="session-title">Session Data</h3>
        <div class="info-row">
            <span class="key">Username</span>
            <span class="value"><?= $name ?></span>
        </div>
        <div class="info-row">
            <span class="key">Email</span>
            <span class="value"><?= $email ?></span>
        </div>
        <div class="info-row">
            <span class="key">Session ID</span>
            <span class="value"><?= session_id() ?></span>
        </div>
    </div>

    <!-- Cookie data -->
    <div class="info-box">
        <h3 class="cookie-title">Cookie Data</h3>
        <?php
        $cookies = [
            'fastlane_remember'        => 'Remember Me Username',
            'fastlane_last_login'      => 'Last Login Time',
            'fastlane_last_registered' => 'Last Registered User',
        ];
        $found = false;
        foreach ($cookies as $key => $label):
            if (isset($_COOKIE[$key])):
                $found = true;
        ?>
        <div class="info-row">
            <span class="key"><?= $label ?></span>
            <span class="value"><?= htmlspecialchars($_COOKIE[$key]) ?></span>
        </div>
        <?php
            endif;
        endforeach;
        if (!$found): ?>
            <div class="no-cookie">No cookies set yet. Try logging out and using "Remember me".</div>
        <?php endif; ?>
    </div>

</div>
</body>
</html>